<?php

namespace Drupal\moby_help\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Moby help entity revision.
 *
 * @ingroup moby_help
 */
class MobyHelpEntityRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Moby help entity revision.
   *
   * @var \Drupal\moby_help\Entity\MobyHelpEntityInterface
   */
  protected $revision;

  /**
   * The Moby help entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $mobyHelpEntityStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->mobyHelpEntityStorage = $container->get('entity_type.manager')->getStorage('moby_help_entity');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'moby_help_entity_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => format_date($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.moby_help_entity.version_history', ['moby_help_entity' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $moby_help_entity_revision = NULL) {
    $this->revision = $this->MobyHelpEntityStorage->loadRevision($moby_help_entity_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->MobyHelpEntityStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Moby help entity: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Moby help entity %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.moby_help_entity.canonical',
       ['moby_help_entity' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {moby_help_entity_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.moby_help_entity.version_history',
         ['moby_help_entity' => $this->revision->id()]
      );
    }
  }

}

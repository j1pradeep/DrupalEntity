<?php

namespace Drupal\moby_help_with_bundle\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for reverting a Moby help with bundle entity revision.
 *
 * @ingroup moby_help_with_bundle
 */
class MobyHelpWithBundleEntityRevisionRevertForm extends ConfirmFormBase {

  /**
   * The Moby help with bundle entity revision.
   *
   * @var \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface
   */
  protected $revision;

  /**
   * The Moby help with bundle entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $mobyHelpWithBundleEntityStorage;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->mobyHelpWithBundleEntityStorage = $container->get('entity_type.manager')->getStorage('moby_help_with_bundle_entity');
    $instance->dateFormatter = $container->get('date.formatter');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'moby_help_with_bundle_entity_revision_revert_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to revert to the revision from %revision-date?', [
      '%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.moby_help_with_bundle_entity.version_history', ['moby_help_with_bundle_entity' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Revert');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $moby_help_with_bundle_entity_revision = NULL) {
    $this->revision = $this->MobyHelpWithBundleEntityStorage->loadRevision($moby_help_with_bundle_entity_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // The revision timestamp will be updated when the revision is saved. Keep
    // the original one for the confirmation message.
    $original_revision_timestamp = $this->revision->getRevisionCreationTime();

    $this->revision = $this->prepareRevertedRevision($this->revision, $form_state);
    $this->revision->revision_log = $this->t('Copy of the revision from %date.', [
      '%date' => $this->dateFormatter->format($original_revision_timestamp),
    ]);
    $this->revision->save();

    $this->logger('content')->notice('Moby help with bundle entity: reverted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Moby help with bundle entity %title has been reverted to the revision from %revision-date.', ['%title' => $this->revision->label(), '%revision-date' => $this->dateFormatter->format($original_revision_timestamp)]));
    $form_state->setRedirect(
      'entity.moby_help_with_bundle_entity.version_history',
      ['moby_help_with_bundle_entity' => $this->revision->id()]
    );
  }

  /**
   * Prepares a revision to be reverted.
   *
   * @param \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface $revision
   *   The revision to be reverted.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface
   *   The prepared revision ready to be stored.
   */
  protected function prepareRevertedRevision(MobyHelpWithBundleEntityInterface $revision, FormStateInterface $form_state) {
    $revision->setNewRevision();
    $revision->isDefaultRevision(TRUE);
    $revision->setRevisionCreationTime(REQUEST_TIME);

    return $revision;
  }

}

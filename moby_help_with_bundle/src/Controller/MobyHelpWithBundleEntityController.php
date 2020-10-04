<?php

namespace Drupal\moby_help_with_bundle\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MobyHelpWithBundleEntityController.
 *
 *  Returns responses for Moby help with bundle entity routes.
 */
class MobyHelpWithBundleEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Moby help with bundle entity revision.
   *
   * @param int $moby_help_with_bundle_entity_revision
   *   The Moby help with bundle entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($moby_help_with_bundle_entity_revision) {
    $moby_help_with_bundle_entity = $this->entityTypeManager()->getStorage('moby_help_with_bundle_entity')
      ->loadRevision($moby_help_with_bundle_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('moby_help_with_bundle_entity');

    return $view_builder->view($moby_help_with_bundle_entity);
  }

  /**
   * Page title callback for a Moby help with bundle entity revision.
   *
   * @param int $moby_help_with_bundle_entity_revision
   *   The Moby help with bundle entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($moby_help_with_bundle_entity_revision) {
    $moby_help_with_bundle_entity = $this->entityTypeManager()->getStorage('moby_help_with_bundle_entity')
      ->loadRevision($moby_help_with_bundle_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $moby_help_with_bundle_entity->label(),
      '%date' => $this->dateFormatter->format($moby_help_with_bundle_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Moby help with bundle entity.
   *
   * @param \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface $moby_help_with_bundle_entity
   *   A Moby help with bundle entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(MobyHelpWithBundleEntityInterface $moby_help_with_bundle_entity) {
    $account = $this->currentUser();
    $moby_help_with_bundle_entity_storage = $this->entityTypeManager()->getStorage('moby_help_with_bundle_entity');

    $langcode = $moby_help_with_bundle_entity->language()->getId();
    $langname = $moby_help_with_bundle_entity->language()->getName();
    $languages = $moby_help_with_bundle_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $moby_help_with_bundle_entity->label()]) : $this->t('Revisions for %title', ['%title' => $moby_help_with_bundle_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all moby help with bundle entity revisions") || $account->hasPermission('administer moby help with bundle entity entities')));
    $delete_permission = (($account->hasPermission("delete all moby help with bundle entity revisions") || $account->hasPermission('administer moby help with bundle entity entities')));

    $rows = [];

    $vids = $moby_help_with_bundle_entity_storage->revisionIds($moby_help_with_bundle_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\moby_help_with_bundle\MobyHelpWithBundleEntityInterface $revision */
      $revision = $moby_help_with_bundle_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $moby_help_with_bundle_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.moby_help_with_bundle_entity.revision', [
            'moby_help_with_bundle_entity' => $moby_help_with_bundle_entity->id(),
            'moby_help_with_bundle_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $moby_help_with_bundle_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.moby_help_with_bundle_entity.translation_revert', [
                'moby_help_with_bundle_entity' => $moby_help_with_bundle_entity->id(),
                'moby_help_with_bundle_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.moby_help_with_bundle_entity.revision_revert', [
                'moby_help_with_bundle_entity' => $moby_help_with_bundle_entity->id(),
                'moby_help_with_bundle_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.moby_help_with_bundle_entity.revision_delete', [
                'moby_help_with_bundle_entity' => $moby_help_with_bundle_entity->id(),
                'moby_help_with_bundle_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['moby_help_with_bundle_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}

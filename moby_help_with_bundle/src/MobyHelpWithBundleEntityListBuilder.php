<?php

namespace Drupal\moby_help_with_bundle;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Moby help with bundle entity entities.
 *
 * @ingroup moby_help_with_bundle
 */
class MobyHelpWithBundleEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Moby help with bundle entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.moby_help_with_bundle_entity.edit_form',
      ['moby_help_with_bundle_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}

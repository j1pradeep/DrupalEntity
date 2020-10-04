<?php

namespace Drupal\moby_help;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Moby help entity entities.
 *
 * @ingroup moby_help
 */
class MobyHelpEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Moby help entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\moby_help\Entity\MobyHelpEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.moby_help_entity.edit_form',
      ['moby_help_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}

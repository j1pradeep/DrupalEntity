<?php

namespace Drupal\moby_help\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Moby help entity entities.
 */
class MobyHelpEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}

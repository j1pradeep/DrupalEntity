<?php

namespace Drupal\moby_help_with_bundle\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Moby help with bundle entity entities.
 */
class MobyHelpWithBundleEntityViewsData extends EntityViewsData {

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

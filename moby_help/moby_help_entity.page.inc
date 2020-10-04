<?php

/**
 * @file
 * Contains moby_help_entity.page.inc.
 *
 * Page callback for Moby help entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Moby help entity templates.
 *
 * Default template: moby_help_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_moby_help_entity(array &$variables) {
  // Fetch MobyHelpEntity Entity Object.
  $moby_help_entity = $variables['elements']['#moby_help_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

<?php

/**
 * @file
 * Contains smartphones_entity.page.inc.
 *
 * Page callback for Smartphones entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Smartphones entity templates.
 *
 * Default template: smartphones_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_smartphones_entity(array &$variables) {
  // Fetch SmartphonesEntity Entity Object.
  $smartphones_entity = $variables['elements']['#smartphones_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

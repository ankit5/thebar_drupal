<?php

/**
 * @file
 * Preprocessors for fieldgroup bootstrap elements.
 */

use Drupal\Core\Template\Attribute;

/**
 * Prepares variables for horizontal tabs templates.
 *
 * Default template: field-group-bootstrap-tabs.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties and children of
 *     the details element. Properties used: #children.
 */
function template_preprocess_field_group_bootstrap_tabs(array &$variables) {
  $element = $variables['element'];
  $variables['navigation'] = (!empty($element['#navigation'])) ? $element['#navigation'] : '';
  $variables['children'] = (!empty($element['#children'])) ? $element['#children'] : '';
  $variables['direction'] = (!empty($element['#direction'])) ? $element['#direction'] : '';
  $variables['mode'] = (!empty($element['#mode'])) ? $element['#mode'] : '';
  $variables['title'] = (!empty($element['#title'])) ? $element['#title'] : '';
}

/**
 * Prepares variables for horizontal scrollby templates.
 *
 * Default template: field-group-bootstrap-scrollby.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties and children of
 *     the details element. Properties used: #children.
 */
function template_preprocess_field_group_bootstrap_scrollby(array &$variables) {
  $element = $variables['element'];
  $variables['navigation'] = (!empty($element['#navigation'])) ? $element['#navigation'] : '';
  $variables['children'] = (!empty($element['#children'])) ? $element['#children'] : '';
  $variables['id'] = (!empty($element['#id'])) ? $element['#id'] : '';
  $variables['label_width'] = (!empty($element['#label_width'])) ? $element['#label_width'] : '';
  $variables['direction'] = (!empty($element['#direction'])) ? $element['#direction'] : '';
}

/**
 * Prepares variables for fieldgroup bootstrap accordion templates.
 *
 * Default template: field-group-bootstrap-accordion.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties and children of
 *     the accordion element. Properties used: #children.
 */
function template_preprocess_field_group_bootstrap_accordion(array &$variables) {
  $element = $variables['element'];
  if (!empty($element["widget"]["#field_name"])) {
    $field_name = $element["widget"]["#field_name"];
  }
  elseif (!empty($element["#group_name"])) {
    $field_name = $element["#group_name"];
  }
  elseif (!empty($element["#field_name"])) {
    $field_name = $element["#field_name"];
  }
  if (!empty($element["widget"]) && !empty($element["widget"]['#title'])) {
    $title = $element["widget"]['#title'];
  }
  elseif (!empty($element['#title'])) {
    $title = $element['#title'];
  }
  $variables['children'] = !empty($element['#children']) ? $element['#children'] : '';
  $variables['id'] = !empty($element['#id']) ? $element['#id'] : '';
  $variables['title'] = !empty($title) ? $title : '';
  $variables['field_name'] = !empty($field_name) ? $field_name : '';
  $variables['parent_id'] = !empty($element["#parent_id"]) ? $element["#parent_id"] : '';
  $variables['collapse'] = !empty($element["#collapse"]) ? $element["#collapse"] : '';
  $variables['button_attributes'] = !empty($element["#button_attributes"]) ? $element["#button_attributes"] : '';
}

/**
 * Prepares variables for fieldgroup bootstrap modal templates.
 *
 * Default template: field-group-bootstrap-modal.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties and children of
 *     the items element. Properties used: #children.
 */
function template_preprocess_field_group_bootstrap_modal(array &$variables) {
  $element = $variables['element'];
  $variables['id'] = (!empty($element['#id'])) ? $element['#id'] : '';
  $variables['children'] = (!empty($element['#children'])) ? $element['#children'] : '';
  $variables['dialog_width'] = (!empty($element['#dialog_width'])) ? $element['#dialog_width'] : '';
  $variables['title'] = (!empty($element['#modal_title'])) ? $element['#modal_title'] : '';
  $variables['footer'] = (!empty($element['#footer'])) ? $element['#footer'] : '';
  $variables['button'] = (!empty($element['#button'])) ? new Attribute($element['#button']['#attributes']) : '';
}

/**
 * Prepares variables for fieldgroup bootstrap horizontal form templates.
 *
 * Default template: field-group-bootstrap-horizontal-form.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties and children of
 *     the items element. Properties used: #children.
 */
function template_preprocess_field_group_bootstrap_horizontal_form(array &$variables) {
  $element = $variables['element'];
  $variables['children'] = (!empty($element['#children'])) ? $element['#children'] : '';
  $variables['id_for'] = (!empty($element['#id_for'])) ? $element['#id_for'] : '';
  $variables['label'] = (!empty($element['#label'])) ? $element['#label'] : '';
  $variables['label_width'] = (!empty($element['#label_width'])) ? $element['#label_width'] : FALSE;
  $variables['required'] = (!empty($element['#required'])) ? 'form-required' : '';
  $variables['term_description'] = (!empty($element['#term_description'])) ? $element['#term_description'] : FALSE;
}

/**
 * Prepares variables for fieldgroup bootstrap grid templates.
 *
 * Default template: field-group-bootstrap-grid.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties and children of
 *     the items element. Properties used: #children.
 */
function template_preprocess_field_group_bootstrap_grid(array &$variables) {
  $element = $variables['element'];
  $variables['children'] = (!empty($element['#children'])) ? $element['#children'] : '';
  $variables['label'] = (!empty($element['#label'])) ? $element['#label'] : '';
}

/**
 * Prepares variables for fieldgroup bootstrap multistep templates.
 *
 * Default template: field-group-bootstrap-multistep.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties and children of
 *     the items element. Properties used: #children.
 */
function template_preprocess_field_group_bootstrap_multistep(array &$variables) {
  $element = $variables['element'];
  $variables['children'] = (!empty($element['#children'])) ? $element['#children'] : '';
  $variables['label'] = (!empty($element['#label'])) ? $element['#label'] : '';
  $variables['show_step_title'] = (!empty($element['#show_step_title'])) ? $element['#show_step_title'] : '';
  $variables['show_step_progress'] = (!empty($element['#show_step_progress'])) ? $element['#show_step_progress'] : '';
  $variables['back_button_text'] = (!empty($element['#back_button_text'])) ? $element['#back_button_text'] : '';
  $variables['next_button_text'] = (!empty($element['#next_button_text'])) ? $element['#next_button_text'] : '';
}

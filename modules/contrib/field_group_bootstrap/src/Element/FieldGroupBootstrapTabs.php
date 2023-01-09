<?php

namespace Drupal\field_group_bootstrap\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element for horizontal bootstrap tabs.
 *
 * Formats all child details and all non-child details whose #group is
 * assigned this element's name as horizontal tabs.
 *
 * @FormElement("field_group_bootstrap_tabs")
 */
class FieldGroupBootstrapTabs extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);

    return [
      '#default_tab' => '',
      '#process' => [
        [$class, 'processFieldGroupBootstrapTabs'],
      ],
      '#theme_wrappers' => ['field_group_bootstrap_tabs'],
    ];
  }

  /**
   * Creates a group formatted as horizontal tabs.
   *
   * @param array $element
   *   An associative array containing the properties and children of the
   *   details element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param bool $on_form
   *   Are the tabs rendered on a form or not.
   *
   * @return array
   *   The processed element.
   */
  public static function processFieldGroupBootstrapTabs(array &$element, FormStateInterface $form_state, $on_form = TRUE) {

    // Inject a new details as child, so that form_process_details() processes
    // this details element like any other details.
    $element['group'] = [
      '#type' => 'details',
      '#theme_wrappers' => [],
      '#parents' => $element['#parents'],
    ];

    // Add an invisible label for accessibility.
    if (empty($element['#title'])) {
      $element['#title_tab'] = t('Bootstrap Tabs');
      $element['#title_display'] = 'invisible';
    }
    $element['#attached']['library'][] = 'field_group_bootstrap/field_group_boostrap';
    return $element;
  }

}

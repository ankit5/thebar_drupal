<?php

namespace Drupal\field_group_bootstrap\Plugin\field_group\FieldGroupFormatter;

use Drupal\Component\Utility\Html;
use Drupal\field_group\FieldGroupFormatterBase;

/**
 * Plugin implementation of the Scrollspy formatter.
 *
 * @FieldGroupFormatter(
 *   id = "bootstrap_floating_labels",
 *   label = @Translation("Bootstrap Floating Labels"),
 *   description = @Translation("This fieldgroup renders child groups in its
 *   own Floating Labels wrapper."), supported_contexts = {
 *     "form",
 *   }
 * )
 */
class BootstrapFloatingLabels extends FieldGroupFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function process(&$element, $processed_object) {

    $element += [
      '#type' => 'container',
      '#title' => $this->getLabel(),
      '#attributes' => [],
    ];
    if ($this->getSetting('id')) {
      $element['#id'] = Html::getUniqueId($this->getSetting('id'));
    }
    $classes = $this->getClasses();
    if (!empty($classes)) {
      if (!empty($element['#attributes']['class'])) {
        foreach ($classes as $class) {
          $element['#attributes']['class'][] = $class;
        }
      }
      else {
        $element['#attributes']['class'] = $classes;
      }
    }
    if ($this->getSetting('show_empty_fields')) {
      $element['show-label-title'] = [
        '#title' => $this->getLabel(),
        '#value' => $this->getLabel(),
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'class' => ['show-label-title', 'display-6'],
          'id' => Html::getId(implode('-', $element["#parents"]) . '-show-label-title'),
        ],
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function preRender(&$element, $rendering_object) {
    parent::preRender($element, $rendering_object);
    $this->process($element, $rendering_object);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm() {
    $form = parent::settingsForm();
    if (!empty($form["show_empty_fields"]["#title"])) {
      $form["show_empty_fields"]["#title"] = $this->t('Show label');
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getClasses() {

    $classes = parent::getClasses();
    $classes[] = 'field-group-' . $this->group->format_type . '-wrapper';
    $classes[] = 'row';
    return $classes;
  }

}

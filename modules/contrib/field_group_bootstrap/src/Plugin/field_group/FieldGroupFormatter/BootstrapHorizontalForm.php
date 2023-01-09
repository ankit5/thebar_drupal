<?php

namespace Drupal\field_group_bootstrap\Plugin\field_group\FieldGroupFormatter;

use Drupal\field\Entity\FieldConfig;
use Drupal\Component\Utility\Html;
use Drupal\field_group\FieldGroupFormatterBase;

/**
 * Plugin implementation of the 'horizontal_form' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "bootstrap_horizontal_form",
 *   label = @Translation("Bootstrap horizontal form"),
 *   description = @Translation("This fieldgroup renders child groups with the grid."),
 *   supported_contexts = {
 *     "form",
 *     "view",
 *   }
 * )
 */
class BootstrapHorizontalForm extends FieldGroupFormatterBase {

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
    if ($this->context == 'view') {
      $entity_type = $this->group->entity_type;
      $bundle = $this->group->bundle;
      foreach ($this->group->children as $field_name) {
        if (isset($element[$field_name]["#access"]) && $element[$field_name]["#access"] == FALSE) {
          continue;
        }
        if (!empty($element[$field_name]['#lazy_builder'])) {
          continue;
        }
        $element[$field_name]["#label_width"] = $this->getSetting('width');
        $element[$field_name]["#attributes"]["class"][] = Html::getClass($field_name);
        if (!empty($element[$field_name]["#title"])) {
          $element[$field_name]["#theme_wrappers"] = ['field_group_bootstrap_horizontal_form'];
          $element[$field_name]["#label"] = $element[$field_name]["#title"];
          $element[$field_name]["#label_display"] = 'hidden';
        }
        elseif (!$this->getSetting('hide_label_if_empty')) {
          $fieldConfig = FieldConfig::loadByName($entity_type, $bundle, $field_name);
          if (!empty($fieldConfig)) {
            $element[$field_name]["#theme_wrappers"] = ['field_group_bootstrap_horizontal_form'];
            $element[$field_name]["#label"] = $fieldConfig->getLabel();
          }
        }
        if ($this->getSetting('term_description')) {
          $element[$field_name]['#term_description'] = $this->getSetting('term_description');
        }
      }
    }
    else {
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

    $form['width'] = [
      '#title' => $this->t('Label width'),
      '#type' => 'select',
      '#options' => array_combine(range(1, 11), range(1, 11)),
      '#default_value' => $this->getSetting('width'),
    ];
    if ($this->context == 'view') {
      $form['hide_label_if_empty'] = [
        '#title' => $this->t('Hide the label if empty'),
        '#description' => $this->t('Do not output any label or container markup if there are no rows with values.'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('hide_label_if_empty'),
      ];
      $form['term_description'] = [
        '#title' => $this->t('Terms and descriptions'),
        '#description' => $this->t('Use for view manager display. Align terms and descriptions horizontally.'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('term_description'),
      ];
    }
    elseif (!empty($form["show_empty_fields"]["#title"])) {
      $form["show_empty_fields"]["#title"] = $this->t('Show label');
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->t('Label width: @width',
      ['@width' => $this->getSetting('width')]
    );
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    return [
      'width' => 2,
      'hide_label_if_empty' => TRUE,
      'term_description' => FALSE,
    ] + parent::defaultContextSettings($context);
  }

  /**
   * {@inheritdoc}
   */
  public function getClasses() {

    $classes = parent::getClasses();
    $classes[] = 'field-group-' . $this->group->format_type . '-wrapper';

    if ($this->getSetting('term_description')) {
      $classes[] = 'row';
    }
    return $classes;
  }

}

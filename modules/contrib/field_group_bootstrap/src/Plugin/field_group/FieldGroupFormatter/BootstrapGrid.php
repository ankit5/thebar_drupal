<?php

namespace Drupal\field_group_bootstrap\Plugin\field_group\FieldGroupFormatter;

use Drupal\field\Entity\FieldConfig;
use Drupal\Component\Utility\Html;
use Drupal\field_group\FieldGroupFormatterBase;

/**
 * Plugin implementation of the 'grid' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "bootstrap_grid",
 *   label = @Translation("Bootstrap grid"),
 *   description = @Translation("This fieldgroup renders child groups with the
 *   grid."), supported_contexts = {
 *     "form",
 *     "view",
 *   }
 * )
 */
class BootstrapGrid extends FieldGroupFormatterBase {

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
    $col = $this->getSetting('width');
    $classes[] = 'row';
    $classes[] = 'row-cols-1';
    $classes[] = 'row-cols-md-' . $col;

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
        $element[$field_name]["#col_width"] = $col;
        $element[$field_name]["#attributes"]["class"][] = 'col';
        $element[$field_name]["#attributes"]["class"][] = Html::getClass($field_name);
        if (!empty($element[$field_name]["#title"])) {
          $element[$field_name]["#theme_wrappers"] = ['field_group_bootstrap_grid'];
          $element[$field_name]["#label"] = $element[$field_name]["#title"];
          $element[$field_name]["#label_display"] = 'hidden';
        }
        elseif (!$this->getSetting('hide_label_if_empty')) {
          $fieldConfig = FieldConfig::loadByName($entity_type, $bundle, $field_name);
          if (!empty($fieldConfig)) {
            $element[$field_name]["#theme_wrappers"] = ['field_group_bootstrap_grid'];
            $element[$field_name]["#label"] = $fieldConfig->getLabel();
          }
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
    $colRange = range(1, 6);
    $form['width'] = [
      '#title' => $this->t('Number of Column'),
      '#type' => 'select',
      '#options' => array_combine($colRange, $colRange),
      '#default_value' => $this->getSetting('width'),
    ];
    if ($this->context == 'view') {
      $form['hide_label_if_empty'] = [
        '#title' => $this->t('Hide the label if empty'),
        '#description' => $this->t('Do not output any label or container markup if there are no rows with values.'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('hide_label_if_empty'),
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
    $summary[] = $this->t('Number of Column: @width',
      ['@width' => $this->getSetting('width')]
    );
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    return ['width' => 3, 'hide_label_if_empty' => TRUE] +
      parent::defaultContextSettings($context);
  }

  /**
   * {@inheritdoc}
   */
  public function getClasses() {

    $classes = parent::getClasses();
    $classes[] = 'field-group-' . $this->group->format_type . '-wrapper';
    return $classes;
  }

}

<?php

namespace Drupal\field_group_bootstrap\Plugin\field_group\FieldGroupFormatter;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;
use Drupal\field_group\FieldGroupFormatterBase;
use Drupal\field\Entity\FieldConfig;

/**
 * Plugin implementation of the 'horizontal_tabs' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "bootstrap_accordion",
 *   label = @Translation("Bootstrap Accordion"),
 *   description = @Translation("This fieldgroup renders child groups in its
 *   own Accordion wrapper."), supported_contexts = {
 *     "form",
 *     "view",
 *   }
 * )
 */
class BootstrapAccordion extends FieldGroupFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function process(&$element, $processed_object) {
    $build_header = [];
    $active_accordion = '';

    $element_id = Html::getUniqueId($this->group->group_name);
    if ($this->getSetting('id')) {
      $element_id = $this->getSetting('id');
    }
    parent::preRender($element, $processed_object);
    if (!empty($fields = $this->group->children)) {
      $active_accordion = $fields[0];
      if (!empty($_COOKIE['bootstrap_accordion'])) {
        $active_accordion = Json::decode($_COOKIE['bootstrap_accordion']);
        if (!empty($active_accordion[$this->group->group_name])) {
          $active_accordion = $active_accordion[$this->group->group_name];
        }
      }
      foreach ($fields as $index => $field_name) {
        $unprocessed_id = $title = '';
        $button = [
          '#attributes' => [
            'class' => ['accordion-button'],
            'id' => Html::getId("accordion-" . $field_name),
            'data-group' => $this->group->group_name,
            'data-bs-toggle' => "collapse",
            'data-controls' => $field_name,
            'aria-expanded' => "false",
          ],
        ];
        if ($active_accordion == $field_name) {
          $button['#attributes']['class'][] = 'active';
          $button['#attributes']['aria-expanded'] = 'true';
        }
        switch ($this->context) {
          case 'view':
            if (!empty($element[$field_name]['#lazy_builder'])) {
              continue;
            }
            if (!empty($processed_object["#fieldgroups"][$field_name])) {
              $unprocessed_id = $processed_object["#fieldgroups"][$field_name]->group_name;
              $title = $processed_object["#fieldgroups"][$field_name]->label;
            }
            elseif (!empty($processed_object[$field_name]['#field_name'])) {
              $unprocessed_id = $processed_object[$field_name]['#field_name'];
              $title = !empty($processed_object[$field_name]['#title']) ? $processed_object[$field_name]['#title'] : '';
            }
            else {
              // Field empty or not accessible.
              continue 2;
            }
            $element[$field_name]["#theme_wrappers"] = ['field_group_bootstrap_accordion'];
            $element[$field_name]["#title"] = $title;
            $element[$field_name]["#parent_id"] = $element_id;
            $element[$field_name]["#collapse"] = $active_accordion == $field_name ? 'show' : '';
            break;

          case 'form':
            if (!empty($processed_object["#fieldgroups"][$field_name])) {
              $unprocessed_id = 'edit-' . $processed_object["#fieldgroups"][$field_name]->group_name;
              $title = $processed_object["#fieldgroups"][$field_name]->label;
            }
            elseif (!empty($processed_object[$field_name])) {
              $unprocessed_id = 'edit-' . implode('-', $processed_object[$field_name]['#parents']);
              $title = $processed_object[$field_name]['widget']['#title'];
            }
            break;
        }
        $button['#title'] = $title;
        $unprocessed_id = Html::getId($unprocessed_id);
        $button['#attributes']['data-bs-target'] = '#' . $unprocessed_id;
        $button['#attributes']['aria-controls'] = $unprocessed_id;
        if ($this->context == 'view') {
          $element[$field_name]["#id"] = $unprocessed_id;
          $element[$field_name]["#button_attributes"] = new Attribute($button["#attributes"]);
        }
        $build_header[$field_name] = $button;
      }
    }
    $element += [
      '#id' => $element_id,
      '#type' => 'container',
      '#tree' => TRUE,
      '#parents' => [$this->group->group_name],
      '#default_tab' => $active_accordion,
      '#items' => $build_header,
      '#attributes' => [
        'class' => $this->getClasses(),
      ],
    ];
    if ($this->context == 'view') {
      if (empty($element['#attributes']['id'])) {
        $element['#attributes']['id'] = $element_id;
      }
    }
    // By default tabs don't have titles but you can override it in the theme.
    if ($this->getLabel()) {
      $element['#title'] = $this->getLabel();
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
    $options = [];
    if (!empty($fields = $this->group->children)) {
      $entity_type = $this->group->entity_type;
      $bundle = $this->group->bundle;
      foreach ($fields as $field_name) {
        $fieldConfig = FieldConfig::loadByName($entity_type, $bundle, $field_name);
        if (!empty($fieldConfig)) {
          $options[$field_name] = $fieldConfig->getLabel();
        }
      }
    }

    $form = parent::settingsForm();
    $form['active_default'] = [
      '#title' => $this->t('Accordion default'),
      '#type' => 'select',
      '#options' => $options,
      '#empty_option' => $this->t('Default'),
      '#default_value' => $this->getSetting('active_default'),
    ];

    $form['flush'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Remove the default background color'),
      '#default_value' => $this->getSetting('flush'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {

    $entity_type = $this->group->entity_type;
    $bundle = $this->group->bundle;
    $summary = parent::settingsSummary();
    if (!empty($this->getSetting('active_default'))) {
      $fieldConfig = FieldConfig::loadByName($entity_type, $bundle, $this->getSetting('active_default'));
      $summary[] = $this->t('Accordion default: @active_default',
        ['@active_default' => $fieldConfig->getLabel()]
      );
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    return ['active_default' => ''] +
      ['flush' => FALSE] +
      parent::defaultContextSettings($context);
  }

  /**
   * {@inheritdoc}
   */
  public function getClasses() {
    $classes = parent::getClasses();
    $classes[] = 'field-group-' . $this->group->format_type . '-wrapper';
    $classes[] = 'accordion';
    if ($this->getSetting('flush')) {
      $classes[] = 'accordion-flush';
    }
    return $classes;
  }

}

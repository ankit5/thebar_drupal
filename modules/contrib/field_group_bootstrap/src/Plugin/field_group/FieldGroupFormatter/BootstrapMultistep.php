<?php

namespace Drupal\field_group_bootstrap\Plugin\field_group\FieldGroupFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;
use Drupal\field_group\FieldGroupFormatterBase;

/**
 * Plugin implementation of the Multistep formatter.
 *
 * @FieldGroupFormatter(
 *   id = "bootstrap_multistep",
 *   label = @Translation("Bootstrap multistep"),
 *   description = @Translation("This fieldgroup renders child groups in its own multistep wrapper."),
 *   supported_contexts = {
 *     "form",
 *     "view",
 *   }
 * )
 */
class BootstrapMultistep extends FieldGroupFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function process(&$element, $processed_object) {

    if (!empty($fields = $this->group->children)) {
      foreach ($fields as $index => $field_name) {
        $unprocessed_id = '';
        switch ($this->context) {
          case 'view':
            if (!empty($element[$field_name]['#lazy_builder'])) {
              continue 2;
            }
            if (!empty($processed_object["#fieldgroups"][$field_name])) {
              $unprocessed_id = $processed_object["#fieldgroups"][$field_name]->group_name;
            }
            elseif (!empty($processed_object[$field_name]['#field_name'])) {
              $unprocessed_id = $processed_object[$field_name]['#field_name'];
            }
            else {
              // Field empty or not accessible.
              continue 2;
            }
            break;

          case 'form':
            if (!empty($processed_object["#fieldgroups"][$field_name])) {
              $unprocessed_id = 'edit-' . $processed_object["#fieldgroups"][$field_name]->group_name;
            }
            elseif (!empty($processed_object[$field_name])) {
              $unprocessed_id = 'edit-' . implode('-', $processed_object[$field_name]['#parents']);
            }
            break;
        }
        $unprocessed_id = Html::getId($unprocessed_id);
        $attr = [
          'id' => $unprocessed_id,
          'class' => ['step', $index ? 'd-none' : 'd-block'],
          'data-step' => $index,
        ];
        if (!empty($processed_object["#fieldgroups"][$field_name])) {
          $groupSettings = $processed_object["#fieldgroups"][$field_name]->format_settings;
          if (!empty($groupSettings['classes'])) {
            $explode_class = explode(' ', $groupSettings["classes"]);
            $attr["class"] = array_merge($attr["class"], $explode_class);
            $attr["class"] = array_unique($attr['class']);
          }
          $attributes = 'data-step="' . $index . '"';
          $processed_object["#fieldgroups"][$field_name]->format_settings["id"] = $attr['id'];
          $explode_class = explode(' ', $processed_object["#fieldgroups"][$field_name]->format_settings["classes"]);
          $merge_class = !empty($explode_class) ? array_merge($explode_class, $attr['class']) : $attr['class'];
          $processed_object["#fieldgroups"][$field_name]->format_settings["attributes"] = $attributes;
          $processed_object["#fieldgroups"][$field_name]->format_settings["classes"] = implode(' ', array_unique($merge_class));
        }
        if (!empty($element[$field_name]["#attributes"]) && is_array($element[$field_name]["#attributes"])) {
          $field_name_class = [];
          if (!empty($element[$field_name]["#attributes"]['class'])) {
            $field_name_class = $element[$field_name]["#attributes"]['class'];
          }
          if (is_object($element[$field_name]["#attributes"])) {
            $field_name_class = $element[$field_name]["#attributes"]->getClass();
          }
          $attr['class'] = array_merge($attr['class'], $field_name_class);
        }
        $element[$field_name]["#attributes"] = new Attribute($attr);
      }
    }
    $back_button_text = (string) $this->getSetting('back_button_text');
    $next_button_text = (string) $this->getSetting('next_button_text');
    if (empty($back_button_text)) {
      $back_button_text = $this->t('Previous');
    }
    if (empty($next_button_text)) {
      $next_button_text = $this->t('Next');
    }
    $element += [
      '#type' => 'field_group_bootstrap_multistep',
      '#attributes' => [],
      '#label' => $this->getLabel(),
      '#show_step_title' => $this->getSetting('show_step_title'),
      '#back_button_text' => $back_button_text,
      '#next_button_text' => $next_button_text,
      '#show_step_progress' => $this->getSetting('show_step_progress'),
    ];
    $element['#attached']['library'][] = 'field_group_bootstrap/multistep';

    if ($this->getSetting('id')) {
      $element['#id'] = Html::getUniqueId($this->getSetting('id'));
    }

    $classes = $this->getClasses();
    if (!empty($classes)) {
      $element['#attributes'] += ['class' => $classes];
    }

    if ($this->getSetting('required_fields')) {
      $element['#attached']['library'][] = 'field_group/core';
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

    $form['label']['#title'] = $this->t('Step title');

    $form['show_step_progress'] = [
      '#title' => $this->t('Show progress bar'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('show_step_progress'),
    ];
    $form['show_step_title'] = [
      '#title' => $this->t('Show step title'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('show_step_title'),
      '#description' => $this->t('Show step title'),
    ];

    $form['back_button_text'] = [
      '#title' => $this->t('Text for back button'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('back_button_text'),
      '#description' => $this->t('Text which will be show on back button'),
    ];

    $form['next_button_text'] = [
      '#title' => $this->t('Text for next button'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('next_button_text'),
      '#description' => $this->t('Text which will be show on next button'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    $summary[] = $this->t('Show progress bar: @element',
      ['@element' => $this->getSetting('show_step_progress') ? 'Show' : 'Hide']
    );

    $summary[] = $this->t('Show title: @element',
      ['@element' => $this->getSetting('show_step_title') ? 'Show' : 'Hide']
    );

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    $defaults = [
      'show_step_progress' => TRUE,
      'back_button_text' => t('Back'),
      'next_button_text' => t('Next'),
      'show_step_title' => FALSE,
    ] + parent::defaultSettings($context);

    if ($context == 'form') {
      $defaults['required_fields'] = 1;
    }

    return $defaults;
  }

}

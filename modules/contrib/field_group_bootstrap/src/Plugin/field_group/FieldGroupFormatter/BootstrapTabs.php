<?php

namespace Drupal\field_group_bootstrap\Plugin\field_group\FieldGroupFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;
use Drupal\field_group\FieldGroupFormatterBase;

/**
 * Plugin implementation of the 'horizontal_tabs' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "bootstrap_tabs",
 *   label = @Translation("Bootstrap Tabs"),
 *   description = @Translation("This fieldgroup renders child groups in its
 *   own tabs wrapper."), supported_contexts = {
 *     "form",
 *     "view",
 *   }
 * )
 */
class BootstrapTabs extends FieldGroupFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function process(&$element, $processed_object) {

    if (!empty($fields = $this->group->children)) {
      $build_nav = [];
      $nav_mode = $this->getSetting('mode');

      foreach ($fields as $index => $field_name) {
        $unprocessed_id = $title = '';
        switch ($this->context) {
          case 'view':
            if (!empty($element[$field_name]['#lazy_builder'])) {
              continue 2;
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
            break;

          case 'form':
            if (!empty($processed_object["#fieldgroups"][$field_name])) {
              $unprocessed_id = 'edit-' . $processed_object["#fieldgroups"][$field_name]->group_name;
              $title = $processed_object["#fieldgroups"][$field_name]->label;
            }
            elseif (!empty($processed_object[$field_name])) {
              $unprocessed_id = 'edit-' . implode('-', $processed_object[$field_name]['#parents']);
              if (!empty($processed_object[$field_name]['widget']['#title'])) {
                $title = $processed_object[$field_name]['widget']['#title'];
              }
              if (empty($title) && !empty($processed_object[$field_name]['widget']['title'])) {
                $title = $processed_object[$field_name]['widget']['title'];
              }
              if (empty($title)) {
                $temp_widget = $processed_object[$field_name]['widget'];
                hide_form_title($temp_widget, $title);
              }
            }
            break;
        }
        $unprocessed_id = Html::getId($unprocessed_id);
        if (is_array($title)) {
          $title = \Drupal::service('renderer')->render($title);
        }
        $button = [
          '#title' => $title,
          '#id' => Html::getId("nav-" . $field_name),
          '#attributes' => [
            'class' => ['nav-link'],
            'data-bs-toggle' => $nav_mode,
            'role' => "tab",
            'data-bs-target' => '#' . $unprocessed_id,
            'data-group' => $this->group->group_name,
            'aria-controls' => $field_name,
            'type' => 'button',
            'aria-selected' => "false",
          ],
        ];
        $build_nav[$field_name] = [
          'attributes' => new Attribute($button['#attributes']),
          'label' => $button['#title'],
        ];
        $attr = [
          'id' => $unprocessed_id,
          'class' => ['tab-pane', 'tabs', 'fade'],
          'role' => "tabpanel",
          'aria-labelledby' => Html::getId("nav-" . $field_name),
        ];
        if (!empty($processed_object["#fieldgroups"][$field_name])) {
          $groupSettings = $processed_object["#fieldgroups"][$field_name]->format_settings;
          if (!empty($groupSettings['classes'])) {
            $explode_class = explode(' ', $groupSettings["classes"]);
            $attr["class"] = array_merge($attr["class"], $explode_class);
            $attr["class"] = array_unique($attr['class']);
          }
          $attributes = 'role="tabpanel" aria-labelledby="' . $attr['aria-labelledby'] . '"';
          $processed_object["#fieldgroups"][$field_name]->format_settings["id"] = $attr['id'];
          $processed_object["#fieldgroups"][$field_name]->format_settings["attributes"] = $attributes;
          $processed_object["#fieldgroups"][$field_name]->format_settings["classes"] = implode(' ', $attr['class']);
        }
        if (!empty($processed_object[$field_name]) && empty($processed_object[$field_name]['#id'])) {
          $processed_object[$field_name]['#id'] = $attr['id'];
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
        $element[$field_name]["#id"] = $attr['id'];
        $element[$field_name]["#attributes"] = new Attribute($attr);
        $element[$field_name]['#label_display'] = 'hidden';
      }
    }

    $element += [
      '#type' => 'field_group_bootstrap_tabs',
      '#prefix' => '<div class="' . implode(' ', $this->getClasses()) . '">',
      '#suffix' => '</div>',
      '#navigation' => $build_nav,
      '#tree' => TRUE,
      '#parents' => [$this->group->group_name],
      '#default_tab' => '',
      '#direction' => $this->getSetting('direction'),
      '#mode' => $this->getSetting('mode'),
    ];
    $element['#attached']['library'][] = 'field_group_bootstrap/field_group_boostrap';
    if ($this->getSetting('id')) {
      $element['#id'] = Html::getUniqueId($this->getSetting('id'));
    }

    // By default tabs don't have titles but you can override it in the theme.
    if ($this->getLabel() && $this->getSetting('show_label')) {
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

    $form = parent::settingsForm();

    $form['direction'] = [
      '#title' => $this->t('Direction'),
      '#type' => 'select',
      '#options' => [
        'vertical' => $this->t('Vertical'),
        'horizontal' => $this->t('Horizontal'),
      ],
      '#default_value' => $this->getSetting('direction'),
    ];
    $form['show_label'] = [
      '#title' => $this->t('Show label'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('show_label'),
    ];
    $form['mode'] = [
      '#title' => $this->t('Mode'),
      '#type' => 'select',
      '#options' => [
        'tab' => $this->t('Tabs'),
        'pill' => $this->t('Pills'),
      ],
      '#default_value' => $this->getSetting('mode'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {

    $summary = parent::settingsSummary();
    $summary[] = $this->t(
      'Direction: @direction',
      ['@direction' => $this->getSetting('direction')]
    );
    $summary[] = $this->t(
      'Mode: @mode',
      ['@mode' => $this->getSetting('mode')]
    );

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    return ['direction' => 'horizontal'] +
      ['show_label' => FALSE] +
      ['mode' => 'tab'] +
      parent::defaultContextSettings($context);
  }

  /**
   * {@inheritdoc}
   */
  public function getClasses() {

    $classes = parent::getClasses();
    $classes[] = 'field-group-' . $this->group->format_type . '-wrapper';
    if ($this->getSetting('direction') == 'vertical') {
      $classes[] = 'd-flex align-items-start';
    }
    return $classes;
  }

  /**
   * Arranges elements into groups.
   *
   * This method is useful for non-input elements that can be used in and
   * outside the context of a form.
   *
   * {@inheritDoc}
   */
  public static function processGroup(&$element, FormStateInterface $form_state, &$complete_form) {

    $groups = &$form_state->getGroups();
    $element['#groups'] = &$groups;

    if (isset($element['#group'])) {
      // Add this element to the defined group (by reference).
      $group = $element['#group'];
      $groups[$group][] = &$element;
    }

    return $element;
  }

}

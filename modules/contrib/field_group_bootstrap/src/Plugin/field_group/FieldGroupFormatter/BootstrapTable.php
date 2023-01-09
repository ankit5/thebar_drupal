<?php

namespace Drupal\field_group_bootstrap\Plugin\field_group\FieldGroupFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\field_group\FieldGroupFormatterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'field_group_bootstrap' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "bootstrap_table",
 *   label = @Translation("Bootstrap Table"),
 *   description = @Translation("This fieldgroup renders fields in a 2 mode
 *   vertical and horizontal."), supported_contexts = {
 *     "form",
 *     "view",
 *   }
 * )
 */
class BootstrapTable extends FieldGroupFormatterBase implements ContainerFactoryPluginInterface {

  /**
   * Module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The Entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Constructs a Popup object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param object $group
   *   The group object.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The Entity field manager.
   */
  public function __construct($plugin_id, $plugin_definition, $group, array $settings, $label, ModuleHandlerInterface $module_handler, EntityFieldManagerInterface $entity_field_manager) {
    parent::__construct($plugin_id, $plugin_definition, $group, $settings, $label);
    $this->moduleHandler = $module_handler;
    $this->entityFieldManager = $entity_field_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['group'],
      $configuration['settings'],
      $configuration['label'],
      $container->get('module_handler'),
      $container->get('entity_field.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    return [
      'direction' => 'horizontal',
      'label_visibility' => 'caption',
      'table_row_striping' => FALSE,
      'always_show_field_value' => FALSE,
      'empty_field_placeholder' => '',
      'hide_table_if_empty' => FALSE,
      'custom_header' => '',
      'color_header' => '',
      'responsive' => '',
      'style_options' => [],
    ] + parent::defaultContextSettings($context);
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

    $form['label_visibility'] = [
      '#title' => $this->t('Label visibility'),
      '#description' => $this->t('This option determines how to display the Field group label.'),
      '#type' => 'select',
      '#options' => [
        'hidden' => $this->t('Hidden'),
        'above' => $this->t('Above table'),
        'caption' => $this->t('Table caption'),
      ],
      '#default_value' => $this->getSetting('label_visibility'),
    ];
    $form['always_show_field_value'] = [
      '#title' => $this->t('Always show field value'),
      '#description' => $this->t('Forces row to display even if field have an empty value.'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('always_show_field_value'),
      '#attributes' => ['class' => ['fgt-always-show-field-value']],
    ];
    $form['style_options'] = [
      '#title' => $this->t('Style'),
      '#type' => 'checkboxes',
      '#multiple' => TRUE,
      '#options' => [
        'table-striped' => $this->t('Striped'),
        'table-dark' => $this->t('Dark'),
        'table-bordered' => $this->t('Border'),
        'table-hover' => $this->t('Hover'),
        'table-sm' => $this->t('Small'),
      ],
      '#default_value' => $this->getSetting('style_options'),
    ];
    $form['empty_field_placeholder'] = [
      '#title' => $this->t('Empty field placeholder'),
      '#description' => $this->t('What to display as a content of empty field.'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('empty_field_placeholder'),
      '#states' => ['visible' => ['.fgt-always-show-field-value' => ['checked' => TRUE]]],
    ];
    $form['hide_table_if_empty'] = [
      '#title' => $this->t('Hide the table if empty'),
      '#description' => $this->t('Do not output any table or container markup if there are no rows with values.'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('hide_table_if_empty'),
    ];
    $form['custom_header'] = [
      '#title' => $this->t('Custom header'),
      '#description' => $this->t('Custom header for vertical mode. Use <b>;</b> separated'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('custom_header'),
    ];
    $form['color_header'] = [
      '#title' => $this->t('Color header'),
      '#type' => 'select',
      '#options' => [
        'table-primary' => $this->t('Primary'),
        'table-secondary' => $this->t('Secondary'),
        'table-success' => $this->t('Success'),
        'table-danger' => $this->t('Danger'),
        'table-warning' => $this->t('Warning'),
        'table-info' => $this->t('Information'),
        'table-light' => $this->t('Light'),
        'table-dark' => $this->t('Dark'),
        'table-active' => $this->t('Active'),
      ],
      '#empty_option' => $this->t('Default'),
      '#default_value' => $this->getSetting('color_header'),
    ];
    $form['responsive'] = [
      '#title' => $this->t('Responsive'),
      '#type' => 'select',
      '#options' => [
        'table-responsive' => $this->t('Always responsive'),
        'table-responsive-sm' => $this->t('Breakpoint small'),
        'table-responsive-md' => $this->t('Breakpoint medium'),
        'table-responsive-lg' => $this->t('Breakpoint large'),
        'table-responsive-xl' => $this->t('Breakpoint extra large'),
      ],
      '#empty_option' => $this->t('Default'),
      '#default_value' => $this->getSetting('responsive'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->t('Direction: @direction',
      ['@direction' => $this->getSetting('direction')]
    );
    if (!empty($style = $this->getSetting('style_options'))) {
      $style = array_filter($style, function ($a) {
        return ($a !== 0 and trim($a) != '');
      });
      if (!empty($style)) {
        $summary[] = $this->t('Style: @style',
          ['@style' => implode(', ', $style)]
        );
      }
    }
    if (!empty($this->getSetting('empty_field_placeholder'))) {
      $summary[] = $this->t('Placeholder: @placeholder',
        ['@placeholder' => $this->getSetting('empty_field_placeholder')]
      );
    }
    if (!empty($this->getSetting('color_header'))) {
      $summary[] = $this->t('Color header: @color_header',
        ['@color_header' => $this->getSetting('color_header')]
      );
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function preRender(&$element, $rendering_object) {

    // Keep using preRender parent for BC.
    parent::preRender($element, $rendering_object);

    $element['#mode'] = $this->context;
    $element['#type'] = 'container';
    $element['#attributes']['class'][] = 'field-group-bootstrap-table';
    $element['#attributes']['class'][] = Html::getClass($this->group->group_name);
    $element['#attributes']['class'][] = trim($this->getSetting('classes'));
    if (!empty($this->getSetting('responsive'))) {
      $element['#attributes']['class'][] = $this->getSetting('responsive');
    }
    $style = array_filter($this->getSetting('style_options'), function ($a) {
      return ($a !== 0 and trim($a) != '');
    });
    $element['table'] = [
      '#type' => 'table',
      '#caption' => NULL,
      '#attributes' => [
        'class' => $style,
      ],
    ];
    if (!empty($this->getSetting('id'))) {
      $element['table']['#id'] = Html::getId($this->getSetting('id'));
    }
    switch ($this->getSetting('label_visibility')) {
      case 'above':
        $element['table']['#caption'] = $this->label;
        $element['table']['#attributes']['class'][] = 'caption-top';
        break;

      case 'caption':
        $element['table']['#caption'] = $this->label;
        break;
    }

    $form_state = new FormState();
    if ($this->getSetting('direction') == 'vertical') {
      $element = $this->processVerticalTable($element, $form_state, (array) $rendering_object);
    }
    else {
      $element = $this->processHorizontalTable($element, $form_state, (array) $rendering_object);
    }
    if (!empty($element['#remove_fields'])) {
      foreach ($element['#remove_fields'] as $field_name) {
        if (!empty($element[$field_name])) {
          $element[$field_name]['#access'] = FALSE;
        }
      }
    }
  }

  /**
   * Process table column.
   *
   * @param array $element
   *   The field group render array.
   * @param \Drupal\Core\Form\FormState $form_state
   *   The current state of the form.
   * @param array $rendering_object
   *   The object / entity beÃ¯ng rendered.
   *
   * @return array
   *   An array of processed elements.
   */
  private function processVerticalTable(array $element, FormState $form_state, array $rendering_object) {
    if (isset($element['#access']) && !$element['#access']) {
      return $element;
    }
    if (!empty($fields = $this->group->children)) {
      $count_cardinality = [];
      foreach ($fields as $field_name) {
        $count_cardinality[$field_name] = 0;
        if ($this->context == 'view') {
          if (!empty($rendering_object[$field_name]["#items"])) {
            $count_cardinality[$field_name] = $rendering_object[$field_name]["#items"]->count() - 1;
          }
        }
        elseif ($rendering_object[$field_name]["widget"]["#cardinality"] > 1) {
          $count_cardinality[$field_name] = $rendering_object[$field_name]["widget"]["#cardinality"] - 1;
        }
      }
      $max_cardinality = max($count_cardinality);
      foreach ($fields as $field_name) {
        if (isset($rendering_object[$field_name]["#access"])) {
          if (!$rendering_object[$field_name]["#access"]) {
            continue;
          }
        }

        if ($row = $this->buildRowVertical($rendering_object, $field_name, $max_cardinality)) {
          $element['table'][$field_name] = $row;
          $fields[] = $field_name;
        }
      }
      if (!empty($fields)) {
        $element['#remove_fields'] = $fields;
      }
    }
    if (!empty($this->getSetting('custom_header'))) {
      $headers = explode(';', $this->getSetting('custom_header'));
      foreach ($headers as $index => $header) {
        $element['table']['#header'][$index] = [
          'data' => $header,
          'class' => [$this->getSetting('color_header')],
        ];
      }
    }
    return $element;
  }

  /**
   * Process table row.
   *
   * @param array $element
   *   The field group render array.
   * @param \Drupal\Core\Form\FormState $form_state
   *   The current state of the form.
   * @param array $rendering_object
   *   The object / entity beÃ¯ng rendered.
   *
   * @return array
   *   An array of processed table row contents.
   */
  private function processHorizontalTable(array $element, FormState $form_state, array $rendering_object) {
    if (isset($element['#access']) && !$element['#access']) {
      return $element;
    }
    if (!empty($fields = $this->group->children)) {
      $bundle = $this->group->bundle;
      $entity_type = $this->group->entity_type;
      if (!empty($this->getSetting('custom_header'))) {
        $header_col = explode(';', $this->getSetting('custom_header'));
        $element['table']['#header']['_col_header'] = [
          'data' => array_shift($header_col),
          'class' => [$this->getSetting('color_header')],
        ];
      }
      foreach ($fields as $index => $field_name) {
        if (!empty($rendering_object[$field_name]["#access"])) {
          if (!$rendering_object[$field_name]["#access"]) {
            continue;
          }
        }
        if ($this->context == 'view') {
          if (!empty($rendering_object[$field_name]["#title"])) {
            $element['table']['#header'][$field_name]['data'] = $rendering_object[$field_name]["#title"];
          }
          elseif ($this->getSetting('always_show_field_value')) {
            $field_definitions = $this->entityFieldManager->getFieldDefinitions($entity_type, $bundle);
            $element['table']['#header'][$field_name]['data'] = $field_definitions[$field_name]->getLabel();
          }
        }
        else {
          $element['table']['#header'][$field_name]['data'] = $rendering_object[$field_name]['widget']['#title'];
        }
        $element['table']['#header'][$field_name]['class'] = [
          $this->getSetting('color_header'),
          $field_name,
        ];
        $fields = array_keys($element['table']['#header']);
      }
      $element['table'] = array_merge($element['table'], $this->buildRowHorizontal($rendering_object, $fields));
      if (!empty($fields)) {
        $element['#remove_fields'] = $fields;
      }
    }
    return $element;
  }

  /**
   * Build row vertical.
   *
   * @param array $rendering_object
   *   The object / entity beÃ¯ng rendered.
   * @param string $field_name
   *   The field name.
   * @param int $max_cardinality
   *   The maximum cardinality for the field.
   *
   * @return bool
   *   Returns either TRUE or FALSE.
   */
  private function buildRowVertical(array $rendering_object, $field_name, $max_cardinality = 0) {
    hide_form_title($rendering_object[$field_name]);
    if ($this->context == 'view') {
      // Header item.
      if (!empty($rendering_object[$field_name]['#title'])) {
        $item['label']['data']['#markup'] = $rendering_object[$field_name]['#title'];
      }
      elseif ($this->getSetting('always_show_field_value')) {
        $bundle = $this->group->bundle;
        $entity_type = $this->group->entity_type;
        $field_definitions = $this->entityFieldManager->getFieldDefinitions($entity_type, $bundle);
        $item['label']['data']['#markup'] = $field_definitions[$field_name]->getLabel();
      }
      else {
        return FALSE;
      }
    }
    else {
      $item['label']['data']['#markup'] = $rendering_object[$field_name]['widget']['#title'];
    }
    $item['label']['#wrapper_attributes']['class'] = [
      Html::getClass($field_name),
      $this->getSetting('color_header'),
    ];
    // Data item.
    foreach (range(0, $max_cardinality) as $delta) {
      $item[$delta]['#wrapper_attributes']['class'] = [
        Html::getClass($field_name),
        'column-' . $delta,
      ];
      $item[$delta]['data']['#markup'] = $this->getSetting('empty_field_placeholder');

      switch ($this->context) {
        case 'form':
          if (!empty($rendering_object[$field_name]['widget'][$delta])) {
            if (!empty($rendering_object[$field_name]['widget'][$delta]['_weight'])) {
              unset($rendering_object[$field_name]['widget'][$delta]['_weight']);
            }
            $item[$delta]['data'] = $rendering_object[$field_name]['widget'][$delta];
            $item[$delta]['data']['#parents'] = [$field_name, $delta];
          }

          if ($rendering_object[$field_name]["widget"]["#cardinality"] == -1 && $delta == 0) {
            $item[$delta]['data'] = $rendering_object[$field_name]['widget'];
            $item[$delta]['#wrapper_attributes']['colspan'] = $max_cardinality + 1;
            $item[$delta]['data']['#title'] = '';
            return $item;
          }
          break;

        case 'view':
          if (!empty($rendering_object[$field_name][$delta])) {
            $item[$delta]['data'] = $rendering_object[$field_name][$delta];
          }
          break;
      }

    }
    return $item;
  }

  /**
   * Build row horizontal.
   *
   * @param array $rendering_object
   *   The object / entity beÃ¯ng rendered.
   * @param array $fields
   *   The list of fields.
   *
   * @return array
   *   An array of table row contents.
   */
  private function buildRowHorizontal(array $rendering_object, array $fields) {
    $count_cardinality = $item = [];
    foreach ($fields as $field_name) {
      hide_form_title($rendering_object[$field_name]);
      $count_cardinality[$field_name] = 0;

      if ($this->context == 'view') {
        $count_cardinality[$field_name] = 1;
        if (!empty($rendering_object[$field_name]["#items"])) {
          $count_cardinality[$field_name] = $rendering_object[$field_name]["#items"]->count() - 1;
        }
      }
      elseif (!empty($rendering_object[$field_name]) && $rendering_object[$field_name]["widget"]["#cardinality"] > 1) {
        $count_cardinality[$field_name] = $rendering_object[$field_name]["widget"]["#cardinality"] - 1;
      }
    }
    if (!empty($this->getSetting('custom_header'))) {
      $header_col = explode(';', $this->getSetting('custom_header'));
      array_shift($header_col);
    }
    $max_cardinality = !empty($count_cardinality) ? max($count_cardinality) : 0;
    foreach (range(0, $max_cardinality) as $delta) {
      if (!empty($header_col)) {
        $item[$delta]['_col_header'] = [
          'data' => ['#markup' => !empty($header_col[$delta]) ? $header_col[$delta] : ''],
        ];
      }
      foreach ($fields as $field_name) {
        $data = FALSE;
        if (!empty($rendering_object[$field_name]['widget'][$delta])) {
          if (!empty($rendering_object[$field_name]['widget'][$delta]['_weight'])) {
            unset($rendering_object[$field_name]['widget'][$delta]['_weight']);
          }
          $data = $rendering_object[$field_name]['widget'][$delta];
        }
        if ($this->context == 'view' && !empty($rendering_object[$field_name][$delta])) {
          $data = $rendering_object[$field_name][$delta];
        }
        $item[$delta][$field_name]['#wrapper_attributes'] = [
          'class' => ['row-' . $delta, Html::getClass($field_name)],
        ];
        if ($field_name == '_col_header' && !empty($this->getSetting('color_header'))) {
          $item[$delta][$field_name]['#wrapper_attributes']['class'][] = $this->getSetting('color_header');
        }

        if (empty($data) && !empty($this->getSetting('empty_field_placeholder'))) {
          $data = ['#markup' => $this->getSetting('empty_field_placeholder')];
        }
        if ($data) {
          $item[$delta][$field_name]['data'] = $data;
          $item[$delta][$field_name]['data']['#parents'] = [
            $field_name,
            $delta,
          ];
        }
        if ($this->context == 'form' && !empty($rendering_object[$field_name]) && $rendering_object[$field_name]["widget"]["#cardinality"] == -1) {
          if ($delta == 0) {
            $item[$delta][$field_name]['data'] = $rendering_object[$field_name]['widget'];
            $item[$delta][$field_name]['#wrapper_attributes']['rowspan'] = $max_cardinality + 1;
            $item[$delta][$field_name]['data']['#title'] = '';
          }
          else {
            unset($item[$delta][$field_name]);
          }
        }
      }
    }
    return $item;
  }

}

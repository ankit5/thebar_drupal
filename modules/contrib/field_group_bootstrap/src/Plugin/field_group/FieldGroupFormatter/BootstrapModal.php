<?php

namespace Drupal\field_group_bootstrap\Plugin\field_group\FieldGroupFormatter;

use Drupal\Component\Utility\Html;
use Drupal\field_group\FieldGroupFormatterBase;

/**
 * Plugin implementation of the 'horizontal_tabs' formatter.
 *
 * @FieldGroupFormatter(
 *   id = "bootstrap_modal",
 *   label = @Translation("Bootstrap Modal"),
 *   description = @Translation("This fieldgroup renders child groups in its own modal wrapper."),
 *   supported_contexts = {
 *     "form",
 *     "view",
 *   }
 * )
 */
class BootstrapModal extends FieldGroupFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function process(&$element, $processed_object) {
    $id = Html::getUniqueId($this->getSetting('id'));
    if (empty($id)) {
      $id = 'modal-' . $this->group->group_name;
    }
    $element += [
      '#id' => $id,
      '#type' => 'field_group_bootstrap_modal',
      '#title' => $this->getLabel(),
      '#attributes' => [
        'class' => $this->getClasses(),
      ],
      '#button' => [
        '#type' => 'button',
        '#value' => $this->getLabel(),
        '#attributes' => [
          'class' => ['btn', $this->getSetting('btn_color')],
          'data-bs-toggle' => "modal",
          'data-bs-target' => "#$id",
          'title' => $this->getLabel(),
        ],
      ],
      '#modal_title' => str_replace('"', "'", $this->getSetting('title')),
      '#footer' => $this->getSetting('show_footer'),
      '#dialog_width' => $this->getSetting('width'),
    ];
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
    $form['btn_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Button color'),
      '#default_value' => $this->getSetting('btn_color'),
      '#options' => [
        "btn-primary" => $this->t("Primary"),
        "btn-secondary" => $this->t("Secondary"),
        "btn-success" => $this->t("Success"),
        "btn-danger" => $this->t("Danger"),
        "btn-warning" => $this->t("Warning"),
        "btn-info" => $this->t("Info"),
        "btn-light" => $this->t("Light"),
        "btn-dark" => $this->t("Dark"),
        "btn-link" => $this->t("Link"),
      ],
      '#empty_option' => $this->t('Default'),
    ];
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $this->getSetting('title'),
    ];
    $form['width'] = [
      '#title' => $this->t('Label width'),
      '#type' => 'select',
      '#options' => [
        'modal-sm' => $this->t('Small'),
        'modal-lg' => $this->t('Large'),
        'modal-xl' => $this->t('Extra large'),
        'modal-fullscreen' => $this->t('Full screen'),
        'modal-fullscreen-sm-down' => $this->t('Full screen below sm'),
        'modal-fullscreen-md-down' => $this->t('Full screen below md'),
        'modal-fullscreen-lg-down' => $this->t('Full screen below lg'),
        'modal-fullscreen-xl-down' => $this->t('Full screen below xl'),
        'modal-fullscreen-xxl-down' => $this->t('Full screen below xxl'),
      ],
      '#empty_option' => $this->t('Default'),
      '#default_value' => $this->getSetting('width'),
    ];
    $form['show_footer'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show footer'),
      '#default_value' => $this->getSetting('show_footer'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    if (!empty($this->getSetting('width'))) {
      $summary[] = $this->t('Label width: @width',
        ['@width' => $this->getSetting('width')]
      );
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultContextSettings($context) {
    return [
      'btn_color' => 'btn-primary',
      'width' => '',
      'show_footer' => FALSE,
    ] + parent::defaultContextSettings($context);
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

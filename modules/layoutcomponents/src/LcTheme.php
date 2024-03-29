<?php

namespace Drupal\layoutcomponents;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Layout\LayoutPluginManager;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * General class for Theme hooks.
 */
class LcTheme implements ContainerInjectionInterface {

  /**
   * The Layout Plugin Manager object.
   *
   * @var \Drupal\Core\Layout\LayoutPluginManager
   */
  protected $layoutPluginManager;

  /**
   * The Request object.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * {@inheritdoc}
   */
  public function __construct(LayoutPluginManager $layout_plugin_manager, RouteMatchInterface $route_match) {
    $this->layoutPluginManager = $layout_plugin_manager;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.core.layout'),
      $container->get('current_route_match')
    );
  }

  /**
   * Implements hook_theme() for LC pages.
   *
   * @see \hook_theme()
   */
  public function theme() {
    return [
      'layoutcomponents_block_content' => [
        'render element' => 'elements',
      ],
      'layoutcomponents__slick_region' => [
        'variables' => [
          'content' => NULL,
        ],
        'render element' => 'elements',
      ],
      'layoutcomponents__region' => [
        'variables' => [
          'region' => NULL,
          'key' => NULL,
        ],
      ],
      'layoutcomponents__subregion' => [
        'variables' => [
          'subregion' => NULL,
          'content' => NULL,
        ],
      ],
    ];
  }

  /**
   * Implements hook_theme_suggestions_HOOK() for LC sections.
   *
   * @see \hook_theme_suggestions_HOOK()
   */
  public function themeSuggestionsLayoutLayoutcomponents(array &$suggestions, array $variables, $hook) {
    if ($hook == 'layout__layoutcomponents_base') {
      $classes = $variables['content']['#settings']['section']['styles']['misc']['extra_class'];
      $class = explode(',', $classes);
      if (is_array($class)) {
        $class = $class[0];
      }

      /** @var \Drupal\Core\Layout\LayoutDefinition $layout */
      $layout = $variables['content']['#layout'];

      $suggestions[] = 'layout__layoutcomponents_base__' . $layout->id();

      $node = $this->getNodeFromSectionContent($variables);

      if (!isset($node)) {
        $node = $this->routeMatch->getParameter('node');
      }

      if (isset($node)) {
        $suggestions[] = 'layout__layoutcomponents_base__' . ((!empty($class)) ? ($class . '_') : '') . $layout->id() . '_' . $node->getType();
        $suggestions[] = 'layout__layoutcomponents_base__' . ((!empty($class)) ? ($class . '_') : '') . $layout->id() . '_' . $node->id() . '_' . $node->getType();
      }
    }

    if ($hook == 'layoutcomponents__region') {
      $classes = $variables['region']['styles']['misc']['extra_class'];
      $class = explode(',', $classes);
      if (is_array($class)) {
        $class = $class[0];
      }
      $suggestions[] = 'layoutcomponents__region__' . (!empty($class) ? (str_replace('-', '_', $class) . '_') : '') . $variables['key'];
      $node = $this->getNodeFromRegionContent($variables);

      if (!isset($node)) {
        $node = $this->routeMatch->getParameter('node');
      }

      if (isset($node)) {
        $suggestions[] = 'layoutcomponents__region__' . (!empty($class) ? (str_replace('-', '_', $class) . '_') : '') . $variables['key'] . '_' . (str_replace('-', '_', $node->getType()));
        $suggestions[] = 'layoutcomponents__region__' . (!empty($class) ? (str_replace('-', '_', $class) . '_') : '') . $variables['key'] . '_' . $node->id() . '_' . (str_replace('-', '_', $node->getType()));
      }
    }

    if ($hook == 'layoutcomponents__subregion') {
      /** @var \Drupal\Core\Template\Attribute $attributes */
      $attributes = $variables['subregion']['attributes'];
      $attributes_classes  =$attributes->getClass()->value();

      $node = $this->getNodeFromRegionContent($variables, TRUE);
      if (!isset($node)) {
        $node = $this->routeMatch->getParameter('node');
      }

      $class = '';
      if (count($attributes_classes) > 2) {
        $class = $attributes_classes[0];
      }

      if (isset($node)) {
        $suggestions[] = 'layoutcomponents__subregion__' . $node->getType();
        $suggestions[] = 'layoutcomponents__subregion__' . $node->id();
      }

      if (!empty($class)) {
        $suggestions[] = 'layoutcomponents__subregion__' . $class;
      }
    }
  }

  /**
   * Implements hook_theme_suggestions_HOOK() for LC blocks.
   *
   * @see \hook_theme_suggestions_HOOK()
   */
  public function themeSuggestionsLayoutcomponentsBlockContent(array $variables) {
    $suggestions = [];
    $block_content = $variables['elements']['#block_content'];
    $suggestions[] = 'layoutcomponents_block_content__' . $block_content->bundle();
    $suggestions[] = 'layoutcomponents_block_content__' . $block_content->id();

    return $suggestions;
  }

  /**
   * Method to determine the current node type of section.
   *
   * @param array $variables
   *   The complete array.
   *
   * @return string|NULL
   *   The type of node.
   */
  public function getNodeFromSectionContent(array $variables) {
    /** @var \Drupal\Core\Layout\LayoutDefinition $layout */
    $layout = $variables['content']['#layout'];

    $node = NULL;
    foreach ($layout->getRegionNames() as $delta => $region_name) {
      if (array_key_exists($region_name, $variables['content'])) {
        foreach ($variables['content'][$region_name] as $block) {
          if (!array_key_exists('#base_plugin_id', $block) || $block['#base_plugin_id'] !== 'field_block') {
            continue;
          }
          if (array_key_exists('#object', $block['content']) && $block['content']['#object'] instanceof Node) {
            $node = $block['content']['#object'];
          }
        }
      }
    }

    return $node;
  }

  /**
   * Method to determine the current node type of region.
   *
   * @param array $variables
   *   The complete array.
   *
   * @return string|NULL
   *   The type of node.
   */
  public function getNodeFromRegionContent(array $variables, $isSubregion = FALSE) {
    $node = NULL;
    if ($isSubregion) {
      $content = $variables['content'];
    }
    else {
      $content = $variables['region']['content'];
    }
    foreach ($content as $block) {
      if (is_array($block)) {
        if (array_key_exists('#group', $block)) {
          foreach ($block['#content'] as $delta => $block_content) {
            if (!array_key_exists('#base_plugin_id', $block_content) || $block_content['#base_plugin_id'] !== 'field_block') {
              continue;
            }
            if ($block_content['content']['#object'] instanceof Node) {
              $node = $block_content['content']['#object'];
            }
          }
        }
      }
    }

    return $node;
  }

  /**
   * Preprocess function for block content template.
   */
  public function preprocessLayoutcomponentsBlockContent(&$variables) {
    $variables['content'] = $variables['elements'];
    // Set configurations.
    $block_content = $variables['elements']['#block_content'];
    $variables['plugin_id'] = 'inline-block' . $block_content->bundle();
    $variables['configuration'] = [
      'provider' => 'layout-builder',
    ];
  }

  /**
   * Implements hook_theme_registry_alter() for LC pages.
   *
   * @see \hook_theme_registry_alter()
   */
  public function themeRegistryAlter(&$theme_registry) {
    if (!\Drupal::hasService('plugin.manager.core.layout')) {
      return;
    }

    // Find all Layoutcomponents Layouts.
    $layouts = $this->layoutPluginManager->getDefinitions();
    $layout_theme_hooks = [];

    foreach ($layouts as $info) {
      if ($info->getClass() === 'Drupal\layoutcomponents\Plugin\Layout\LcBase') {
        $layout_theme_hooks[] = $info->getThemeHook();
      }
    }

    foreach ($theme_registry as $theme_hook => $info) {
      if ((in_array($theme_hook, $layout_theme_hooks) || (!empty($info['base hook']) && in_array($info['base hook'], $layout_theme_hooks))) ||
        str_contains($theme_registry[$theme_hook]['template'], 'layout--layoutcomponents-base--')
      ) {
        // Include file.
        $theme_registry[$theme_hook]['includes'][] = \Drupal::service('extension.list.module')->getPath('layoutcomponents') . '/layoutcomponents.theme.inc';
        // Set new preprocess function.
        $theme_registry[$theme_hook]['preprocess functions'][] = '_layoutcomponents_preprocess_layout';
        $theme_registry[$theme_hook]['base hook'] = 'layout__layoutcomponents_base';
      }
    }

    // Remove each default template_preprocess_layout.
    // Regions does not contain 'content' array.
    // If not, layout discovery return an error.
    foreach ($theme_registry['layoutcomponents__region']['preprocess functions'] as $key => $value){
      if ($value == 'template_preprocess_layout') {
        unset($theme_registry['layoutcomponents__region']['preprocess functions'][$key]);
      }
    }

    $theme_registry['layoutcomponents__region']['base hook'] = 'layoutcomponents__region';
    $theme_registry['layoutcomponents__subregion']['base hook'] = 'layoutcomponents__subregion';
  }

  /**
   * Implements hook_help() for LC pages.
   *
   * @see \hook_help()
   */
  public function help($route_name, RouteMatchInterface $route_match) {
    if ($route_match->getRouteObject()->getOption('_layout_builder')) {
      return '';
    }

    switch ($route_name) {
      // Main module help for the layoutcomponents module.
      case 'help.page.layoutcomponents':
        $output = '';
        $output .= '<h3>' . t('About') . '</h3>';
        $output .= '<p>' . t('Block type creation') . '</p>';
        return $output;

      default:
    }
  }

}

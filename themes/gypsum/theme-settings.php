<?php
use Drupal\Core\Form\FormStateInterface;
/**
 * @file
 * Custom setting for gypsum theme.
 */
function gypsum_form_system_theme_settings_alter(&$form, FormStateInterface &$form_state, $form_id = NULL) {
  $img_path = $GLOBALS['base_url'] . '/' . \Drupal::service('extension.list.theme')->getPath('gypsum') . '/images/gypsumpro.jpg';
  $img = '<img src="'.$img_path.'" alt="image" />';
  $form['gypsum'] = [
    '#type'       => 'vertical_tabs',
    '#title'      => '<h3>' . t('New West Gypsum Theme Settings') . '</h3>',
    '#default_tab' => 'general',
  ];
  // General settings tab.
  $form['general'] = [
    '#type'  => 'details',
    '#title' => t('General'),
    '#description' => t('<h3></h3>'),
    '#group' => 'gypsum',
  ];
   
  
  // Social tab.
  $form['social'] = [
    '#type'  => 'details',
    '#title' => t('Social'),
    '#description' => t('Social icons settings. These icons appear in header and footer region.'),
    '#group' => 'gypsum',
  ];
  
  // Footer tab.
  $form['footer'] = [
    '#type'  => 'details',
    '#title' => t('Footer'),
    '#group' => 'gypsum',
  ];

 
  
  // Settings under social tab.
  // Show or hide all icons.
  $form['social']['all_icons'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Show Social Icons'),
  ];
  $form['social']['all_icons']['all_icons_show'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Show social icons in header and footer'),
    '#default_value' => theme_get_setting('all_icons_show'),
    '#description'   => t("Check this option to show social icons in footer. Uncheck to hide."),
  ];

  // Facebook.
    $form['social']['facebook'] = [
    '#type'        => 'details',
    '#title'       => t("Facebook"),
  ];
  $form['social']['facebook']['facebook_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Facebook Url'),
    '#description'   => t("Enter yours facebook profile or page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('facebook_url'),
  ];
  // Twitter.
  $form['social']['twitter'] = [
    '#type'        => 'details',
    '#title'       => t("Twitter"),
  ];
  $form['social']['twitter']['twitter_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Twitter Url'),
    '#description'   => t("Enter yours twitter page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('twitter_url'),
  ];
  // Instagram.
  $form['social']['instagram'] = [
    '#type'        => 'details',
    '#title'       => t("Instagram"),
  ];
  $form['social']['instagram']['instagram_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Instagram Url'),
    '#description'   => t("Enter yours instagram page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('instagram_url'),
  ];
  // Linkedin.
  $form['social']['linkedin'] = [
    '#type'        => 'details',
    '#title'       => t("Linkedin"),
  ];
  $form['social']['linkedin']['linkedin_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('Linkedin Url'),
    '#description'   => t("Enter yours linkedin page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('linkedin_url'),
  ];
  // YouTube.
  $form['social']['youtube'] = [
    '#type'        => 'details',
    '#title'       => t("YouTube"),
  ];
  $form['social']['youtube']['youtube_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('YouTube Url'),
    '#description'   => t("Enter yours youtube.com page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('youtube_url'),
  ];
  // Vimeo.
  $form['social']['vimeo'] = [
    '#type'        => 'details',
    '#title'       => t("Vimeo"),
  ];
  $form['social']['vimeo']['vimeo_url'] = [
    '#type'          => 'textfield',
    '#title'         => t('YouTube Url'),
    '#description'   => t("Enter yours vimeo.com page url. Leave the url field blank to hide this icon."),
    '#default_value' => theme_get_setting('vimeo_url'),
  ];
  // Social -> vk.com url.
   $form['social']['vk'] = [
     '#type'        => 'details',
     '#title'       => t("vk.com"),
   ];
   $form['social']['vk']['vk_url'] = [
       '#type'          => 'textfield',
       '#title'         => t('vk.com'),
       '#description'   => t("Enter yours vk.com page url. Leave the url field blank to hide this icon."),
       '#default_value' => theme_get_setting('vk_url'),
   ];
   // Social -> whatsapp.
   $form['social']['whatsapp'] = [
     '#type'        => 'details',
     '#title'       => t("whatsapp"),
   ];
   $form['social']['whatsapp']['whatsapp_url'] = [
       '#type'          => 'textfield',
       '#title'         => t('WhatsApp'),
       '#description'   => t("Enter yours whatsapp url. Leave the url field blank to hide this icon."),
       '#default_value' => theme_get_setting('whatsapp_url'),
   ];
   // Social -> github.
   $form['social']['github'] = [
     '#type'        => 'details',
     '#title'       => t("Github"),
   ];
   $form['social']['github']['github_url'] = [
       '#type'          => 'textfield',
       '#title'         => t('Github'),
       '#description'   => t("Enter yours github url. Leave the url field blank to hide this icon."),
       '#default_value' => theme_get_setting('github_url'),
   ];
   // Social -> telegram.
   $form['social']['telegram'] = [
     '#type'        => 'details',
     '#title'       => t("Telegram"),
   ];
   $form['social']['telegram']['telegram_url'] = [
     '#type'          => 'textfield',
     '#title'         => t('Telegram'),
     '#description'   => t("Enter yours telegram url. Leave the url field blank to hide this icon."),
     '#default_value' => theme_get_setting('telegram_url'),
   ];

  // Footer -> Copyright.
  $form['footer']['copyright'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Website Copyright Text'),
  ];

 
  // Insert Codes -> css
  $form['insert_codes']['css']['css_custom'] = [
    '#type'        => 'fieldset',
    '#title'       => t('Addtional CSS'),
  ];
  $form['insert_codes']['css']['css_custom']['css_extra'] = [
    '#type'          => 'checkbox',
    '#title'         => t('Enable Addtional CSS'),
    '#default_value' => theme_get_setting('css_extra'),
    '#description'   => t("Check this option to enable additional styling / css. Uncheck to disable this feature."),
  ];
  $form['insert_codes']['css']['css_code_top'] = [
    '#type'          => 'textarea',
    '#title'         => t('Addtional CSS Codes Top'),
    '#default_value' => theme_get_setting('css_code_top'),
    '#description'   => t('Add your own CSS codes here to customize the appearance of your site.<br />Please refer to this tutorial for detail: <a href="https://drupar.com/gypsum-theme-documentation/custom-css" target="_blank">Custom CSS</a>'),
  ];
  $form['insert_codes']['css']['css_code'] = [
    '#type'          => 'textarea',
    '#title'         => t('Addtional CSS Codes'),
    '#default_value' => theme_get_setting('css_code'),
    '#description'   => t('Add your own CSS codes here to customize the appearance of your site.<br />Please refer to this tutorial for detail: <a href="https://drupar.com/gypsum-theme-documentation/custom-css" target="_blank">Custom CSS</a>'),
  ];

 
// End form.
}

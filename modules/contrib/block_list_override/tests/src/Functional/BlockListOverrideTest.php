<?php

namespace Drupal\Tests\block_list_override\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests for the Block List Override module.
 *
 * @group block_list_override
 */
class BlockListOverrideTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'classy';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'block_list_override',
    'block',
    'layout_discovery',
    'layout_builder',
  ];

  /**
    * A simple user.
    */
  private $user;

  /**
   * Perform initial setup tasks that run before every test method.
   */
  public function setUp() {
    parent::setUp();
    $this->user = $this->DrupalCreateUser([
      'administer site configuration',
      'access block list override',
    ]);
  }

  /**
   * Tests that the BlockBlacklist pages can be reached before configuration.
   */
  public function testBlockListPagesExist() {
    $this->drupalLogin($this->user);
    $pages = [
      'admin/config/block_list_override/settings',
      'admin/config/block_list_override/system-list',
      'admin/config/block_list_override/layout-list',
    ];
    foreach ($pages as $page) {
      $this->drupalGet($page);
      $this->assertResponse(200);
    }
    $this->drupalLogout();
  }

  /**
   * Tests the config form.
   */
  public function testBlockListConfigForm() {

    // Array of configuration options with the rule to use and a block the rule
    // should hide.
    $replace = [
      'system_match' => [
        'label' => 'System List Match',
        'rule' => 'help',
        'hides' => 'help',
      ],
      'system_prefix' => [
        'label' => 'System List Prefix',
        'rule' => 'system_menu_block',
        'hides' => 'system_menu_block:admin',
      ],
      'system_regex' => [
        'label' => 'System List Regex',
        'rule' => '/local_(.*)_block/',
        'hides' => 'local_tasks_block',
      ],
      'layout_match' => [
        'label' => 'Layout Builder List Match',
        'rule' => 'page_title_block',
        'hides' => 'page_title_block',
      ],
      'layout_prefix' => [
        'label' => 'Layout Builder List Prefix',
        'rule' => 'inline_block',
        'hides' => 'inline_block:basic',
      ],
      'layout_regex' => [
        'label' => 'Layout Builder List Regex',
        'rule' => '/system_(.*)_block/',
        'hides' => 'system_powered_by_block',
      ],
    ];

    // Login.
    $this->drupalLogin($this->user);

    // Access config page.
    $this->drupalGet('admin/config/block_list_override/settings');
    $this->assertResponse(200);
    // Test the form elements exist and have default values.
    $config = $this->config('block_list_override.settings');

    foreach ($replace as $field => $opt) {
      $this->assertFieldByName(
        $field,
        $config->get('block_list_override.settings.' . $field),
        $field . ' field has the default value'
      );
    }
    // Test form submission.
    $edit = [];
    foreach ($replace as $field => $opt) {
      $edit[$field] = $opt['rule'];
    }
    $this->drupalPostForm(NULL, $edit, t('Save configuration'));
    $this->assertText(
      'The configuration options have been saved.',
      'The form was saved correctly.'
    );
    // Test the new values are there.
    $this->drupalGet('admin/config/block_list_override/settings');
    $this->assertSession()->statusCodeEquals(200);
    foreach ($replace as $field => $opt) {
      $string = strtr('//*[@name=":field_name"]', [':field_name' => $field]);
      $elements = $this->xpath($string);
      $value = count($elements) ? $elements[0]->getValue() : NULL;
      $this->assertEquals($value, $opt['rule']);
    }

    // Test block list results.
    $pages = [
      'system' => 'admin/config/block_list_override/system-list',
      'layout' => 'admin/config/block_list_override/layout-list',
    ];
    foreach ($pages as $type => $page) {
      $this->drupalGet($page);
      $this->assertSession()->statusCodeEquals(200);
      foreach ($replace as $field => $opt) {
        switch ($field) {
          case $type . '_match':
          case $type . '_prefix':
          case $type . '_regex':
            $this->assertNoText($opt['hides'], $opt['label'] . ' block was not displayed .');
            break;

        }
      }
    }
    $this->drupalLogout();
  }

}

<?php

namespace Drupal\manage_module_config\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines manage_module_config annotation object.
 *
 * @Annotation
 */
class ManageModuleConfig extends Plugin {
  
  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;
  
  /**
   * The human-readable name of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;
  
  /**
   * The description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;
  
}

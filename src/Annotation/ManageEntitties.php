<?php

namespace Drupal\manage_module_config\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines manage_entitties annotation object.
 *
 * @Annotation
 */
class ManageEntitties extends Plugin {
  
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
  
  /**
   * Liste des entitées.
   *
   * @var array
   */
  public $entities = [];
  
  /**
   * --
   *
   * @return array
   */
  public function getEntities() {
    return $this->entities;
  }
  
  /**
   * --
   *
   * @param array $entities
   */
  public function setEntities(array $entities) {
    $this->entities = $entities;
  }
  
}

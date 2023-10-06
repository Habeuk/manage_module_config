<?php

namespace Drupal\manage_module_config\ManageEntities;

use Drupal\Core\Url;

/**
 * Interface for manage_module_config plugins.
 */
interface ManageModuleEntitiesInterface {
  
  /**
   * Returns the translated plugin label.
   *
   * @return string The translated title.
   */
  public function label();
  
  /**
   * Retourne le nom de la configuration
   *
   * @return string
   */
  public function GetName();
  
  /**
   * Retorune la description de la configuration
   *
   * @return string
   */
  public function getDescription();
  
  /**
   * Retourne la route de la config
   *
   * @return Url
   */
  public function getBaseRoute();
  
  /**
   * Retourne le nombre d'entité ou null.
   *
   * @return integer|null
   */
  public function getNumbers();
  
}

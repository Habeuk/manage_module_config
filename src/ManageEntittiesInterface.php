<?php

namespace Drupal\manage_module_config;

/**
 * Interface for manage_entitties plugins.
 */
interface ManageEntittiesInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

}

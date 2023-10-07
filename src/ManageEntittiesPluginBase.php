<?php

namespace Drupal\manage_module_config;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for manage_entitties plugins.
 */
abstract class ManageEntittiesPluginBase extends PluginBase implements ManageEntittiesInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

}

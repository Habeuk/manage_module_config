<?php

namespace Drupal\manage_module_config;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Component\Plugin\ConfigurableInterface;

/**
 * Base class for manage_module_config plugins.
 */
abstract class ManageModuleConfigPluginBase extends PluginBase implements ManageModuleConfigInterface, ConfigurableInterface {
  
  /**
   *
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }
  
  /**
   *
   * @return array
   */
  public function defaultConfiguration() {
    return [
      'name' => 'Menu',
      'description' => "Permet gerer les elements du menu ",
      // 'route' => NULL,
      'weight' => 100, // [0-100]
      'icon_svg_class' => 'btn-danger text-white btn-lg',
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
						<path d="M80 368H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm0-320H16A16 16 0 0 0 0 64v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16V64a16 16 0 0 0-16-16zm0 160H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm416 176H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"/>
					</svg>',
      'enable' => false // La configuration ne serra pas charger par defaut.
    ];
  }
  
  public function getIconSvg() {
    return $this->configuration['icon_svg'];
  }
  
  public function getIconSvgClass() {
    return $this->configuration['icon_svg_class'];
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\Component\Plugin\ConfigurableInterface::getConfiguration()
   */
  public function getConfiguration() {
    return $this->configuration;
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\Component\Plugin\ConfigurableInterface::setConfiguration()
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = $configuration;
  }
  
}

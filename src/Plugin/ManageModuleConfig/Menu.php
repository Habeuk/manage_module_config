<?php

namespace Drupal\manage_module_config\Plugin\ManageModuleConfig;

use Drupal\manage_module_config\ManageModuleConfigPluginBase;
use Drupal\Core\Url;

/**
 * Gestion du menu.
 *
 * @ManageModuleConfig(
 *   id = "menu",
 *   label = @Translation("Menu"),
 *   description = @Translation("Foo description.")
 * )
 */
class Menu extends ManageModuleConfigPluginBase {
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageModuleConfigInterface::GetName()
   */
  public function GetName() {
    return $this->configuration['name'];
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageModuleConfigInterface::getRoute()
   */
  public function getRoute() {
    /**
     *
     * @var \Drupal\Core\Http\RequestStack $RequestStack
     */
    $RequestStack = \Drupal::service('request_stack');
    $Request = $RequestStack->getCurrentRequest();
    return Url::fromRoute('lesroidelareno.manage_menu', [], [
      'query' => [
        'destination' => $Request->getPathInfo()
      ]
    ]);
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageModuleConfigInterface::getDescription()
   */
  public function getDescription() {
    return $this->configuration['description'];
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageModuleConfigPluginBase::defaultConfiguration()
   */
  public function defaultConfiguration() {
    return [
      'name' => 'Menu',
      'description' => "Gerer les elements du menu",
      'icon_svg_class' => 'btn-wbu-thirdly text-white btn-lg',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
  
}

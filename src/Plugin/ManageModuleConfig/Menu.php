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
    $url = null;
    if (\Drupal::moduleHandler()->moduleExists('lesroidelareno')) {
      $url = Url::fromRoute('lesroidelareno.manage_menu', [], [
        'query' => [
          'destination' => $Request->getPathInfo()
        ]
      ]);
    }
    else {
      $configs = \Drupal::config("wb_horizon_public.source_site_configs");
      $main_menu_id = $configs->get("main_menu_id") ?? null;
      if (!isset($main_menu_id)) {
        $menuQuery = \Drupal::entityTypeManager()->getStorage("menu")->getQuery();
        $menuQuery->condition("id", "main", 'CONTAINS');
        $menuIds = $menuQuery->execute();
        if (!empty($menuIds)) {
          $main_menu_id = reset($menuIds);
        }
      }
      if (isset($main_menu_id)) {
        $url = Url::fromRoute('entity.menu.edit_form', [
          'menu' => $main_menu_id
        ]);
      }
    }
    return $url;
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
      'name' => t('Menu'),
      'description' => t("Manage menu items"),
      'icon_svg_class' => 'btn-wbu-thirdly text-white btn-lg',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
}

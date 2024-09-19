<?php

namespace Drupal\manage_module_config\Plugin\ManageModuleConfig;

use Drupal\manage_module_config\ManageModuleConfigPluginBase;
use Drupal\Core\Url;
use Drupal\lesroidelareno\lesroidelareno;

/**
 * Gestion du menu.
 *
 * @ManageModuleConfig(
 *   id = "config_theme",
 *   label = @Translation("Configuration du theme"),
 *   description = @Translation("Foo description.")
 * )
 */
class ConfigTheme extends ManageModuleConfigPluginBase {
  
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
    $themeConf = \Drupal::entityTypeManager()->getStorage("config_theme_entity")->loadByProperties([
      'hostname' => lesroidelareno::getCurrentDomainId()
    ]);
    if (!empty($themeConf)) {
      $themeConf = reset($themeConf);
      /**
       *
       * @var \Drupal\Core\Http\RequestStack $RequestStack
       */
      $RequestStack = \Drupal::service('request_stack');
      $Request = $RequestStack->getCurrentRequest();
      return Url::fromRoute('entity.config_theme_entity.edit_form', [
        'config_theme_entity' => $themeConf->id()
      ], [
        'query' => [
          'destination' => $Request->getPathInfo()
        ]
      ]);
    }
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
      'name' => 'Configuration du theme',
      'description' => "Gerer la taille du texte, les couleurs, le logo ...",
      'icon_svg_class' => 'btn-light text-white btn-lg',
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"> <path d="M496 384H160v-16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h80v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h336c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm0-160h-80v-16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h336v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h80c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm0-160H288V48c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16C7.2 64 0 71.2 0 80v32c0 8.8 7.2 16 16 16h208v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h208c8.8 0 16-7.2 16-16V80c0-8.8-7.2-16-16-16z"/></svg>',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
  
}

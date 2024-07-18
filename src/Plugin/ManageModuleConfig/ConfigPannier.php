<?php

namespace Drupal\manage_module_config\Plugin\ManageModuleConfig;

use Drupal\manage_module_config\ManageModuleConfigPluginBase;
use Drupal\Core\Url;

/**
 * Gestion Panier.
 *
 * @ManageModuleConfig(
 *   id = "config_panier",
 *   label = @Translation("Configuration du panier"),
 *   description = @Translation("Foo description.")
 * )
 */
class ConfigPannier extends ManageModuleConfigPluginBase {

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
    return Url::fromRoute('wb_horizon_public.default_config_bydomain', [], []);
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
      'name' => 'Configuration du Panier',
      'description' => "Permet de gérer les élements du pannier",
      'icon_svg_class' => 'btn-lg btn-secondary btn-sm',
      'icon_svg' => '<svg height="1.5em" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg"><circle cx="13.33" cy="29.75" r="2.25" class="clr-i-outline--badged clr-i-outline-path-1--badged"/><circle cx="27" cy="29.75" r="2.25" class="clr-i-outline--badged clr-i-outline-path-2--badged"/><path d="M22.57 7a7.5 7.5 0 0 1-.07-1 7.5 7.5 0 0 1 .07-1H11.49l.65 2Z" class="clr-i-outline--badged clr-i-outline-path-3--badged"/><path d="M30 13.5h-.42L28.33 19h-15L8.76 4.53a1 1 0 0 0-.66-.65L4 2.62a1 1 0 1 0-.59 1.92L7 5.64l4.59 14.5-1.64 1.34-.13.13A2.66 2.66 0 0 0 9.74 25 2.75 2.75 0 0 0 12 26h16.69a1 1 0 0 0 0-2H11.84a.67.67 0 0 1-.56-1l2.41-2h15.44a1 1 0 0 0 1-.78l1.57-6.91a7.5 7.5 0 0 1-1.7.19" class="clr-i-outline--badged clr-i-outline-path-4--badged"/><circle cx="30" cy="6" r="5" class="clr-i-outline--badged clr-i-outline-path-5--badged clr-i-badge"/><path fill="none" d="M0 0h36v36H0z"/></svg>',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
}

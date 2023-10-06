<?php

namespace Drupal\manage_module_config\Plugin\ManageModuleEntities;

use Drupal\manage_module_config\ManageEntities\ManageEntitiesPluginBase;

/**
 * Gestion du menu.
 *
 * @ManageModuleEntities(
 *   id = "manage_entities",
 *   label = @Translation("Manage Entities"),
 *   description = @Translation("Foo description.")
 * )
 */
class ManageEntities extends ManageEntitiesPluginBase {
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntities\ManageModuleEntitiesInterface::GetName()
   */
  public function GetName() {
    return $this->configuration['name'];
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntities\ManageModuleEntitiesInterface::getBaseRoute()
   */
  public function getBaseRoute() {
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntities\ManageModuleEntitiesInterface::getNumbers()
   */
  public function getNumbers() {
    $definitions = $this->getPluginDefinition();
    dump($definitions);
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntities\ManageModuleEntitiesInterface::getDescription()
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
      'name' => 'Contenus et pages',
      'description' => "Gerer les elements du menu",
      'icon_svg_class' => 'Gerer les pages, les articles',
      '#icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M552 64H112c-20.858 0-38.643 13.377-45.248 32H24c-13.255 0-24 10.745-24 24v272c0 30.928 25.072 56 56 56h496c13.255 0 24-10.745 24-24V88c0-13.255-10.745-24-24-24zM48 392V144h16v248c0 4.411-3.589 8-8 8s-8-3.589-8-8zm480 8H111.422c.374-2.614.578-5.283.578-8V112h416v288zM172 280h136c6.627 0 12-5.373 12-12v-96c0-6.627-5.373-12-12-12H172c-6.627 0-12 5.373-12 12v96c0 6.627 5.373 12 12 12zm28-80h80v40h-80v-40zm-40 140v-24c0-6.627 5.373-12 12-12h136c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H172c-6.627 0-12-5.373-12-12zm192 0v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0-144v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0 72v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12z"/></svg>',
      '#icon_svg_class' => 'btn-circle btn-primary text-white btn-lg',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
  
}
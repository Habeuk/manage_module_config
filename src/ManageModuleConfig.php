<?php

namespace Drupal\manage_module_config;

use Stephane888\Debug\Repositories\ConfigDrupal;

/**
 *
 * @author stephane
 *        
 */
class ManageModuleConfig {
  
  /**
   * Options list.
   *
   * @return array
   */
  static public function getPlugins() {
    /**
     *
     * @var \Drupal\manage_module_config\ManageEntitties\ManageEntittiesPluginManager $manage_module_entities
     */
    $manage_module_entities = \Drupal::service('plugin.manager.manage_module_config');
    return $manage_module_entities->getOptionsPlugins();
  }
  
  /**
   * Rescupere les formulaires webform que l'utilisateur peut soumettre.
   *
   * @return array
   */
  static public function getFormWebformByUser(): array {
    return [
      'user_liste_de_blogs' => 'Liste de blogs',
      'user_liste_de_produits' => 'Liste de produits',
      'user_liste_de_realisation' => 'Liste de realisation',
      'user_liste_de_services' => 'Liste de services',
      'user_autres_informations_donnees' => 'Autres informations - données',
      'user_a_propos_de_l_auteur' => 'A propos de l\'auteur',
      'user_information_generale' => 'Information générale'
    ];
  }
}
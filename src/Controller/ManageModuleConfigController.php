<?php

namespace Drupal\manage_module_config\Controller;

use Drupal\Core\Controller\ControllerBase;
use Stephane888\DrupalUtility\HttpResponse;

/**
 * Returns responses for manage module config routes.
 */
class ManageModuleConfigController extends ControllerBase {
  
  /**
   * Builds the response.
   */
  public function build() {
    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!')
    ];
    
    return $build;
  }
  
  /**
   *
   * @param integer $uid
   * @param integer $plugin_id
   */
  public function ManageEntities($plugin_id) {
    $datas = [];
    /**
     *
     * @var \Drupal\manage_module_config\ManageEntitties\ManageEntittiesPluginManager $manage_module_entities
     */
    $manage_module_entities = \Drupal::service('plugin.manager.manage_entitties');
    $manage_module_entities->BuildCollectionOfEnttities($plugin_id, $datas);
    return $datas;
  }
  
  /**
   * --
   */
  public function AddPlugin($domain_id, $site_type_datas_id) {
    $config = [];
    /**
     *
     * @var \Drupal\creation_site_virtuel\Entity\SiteTypeDatas $entityModel
     */
    $entityModel = $this->entityTypeManager()->getStorage("site_type_datas")->load($site_type_datas_id);
    if ($entityModel) {
      // On ajoute les plugins selectionnés:
      /**
       *
       * @var \Drupal\domain_config_ui\Config\Config $config
       */
      $config = \Drupal::service('config.factory')->getEditable('domain.config.' . $domain_id . '.manage_module_config.settings');
      $config->set('plugins', $entityModel->getPlugins());
      $config->save();
      $config = $config->getRawData();
    }
    return HttpResponse::response($config);
  }
  
}

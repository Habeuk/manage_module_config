<?php

namespace Drupal\manage_module_config\Controller;

use Drupal\Core\Controller\ControllerBase;
use Stephane888\DrupalUtility\HttpResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\manage_module_config\ManageEntitties\ManageEntittiesPluginManager;

/**
 * Returns responses for manage module config routes.
 */
class ManageModuleConfigController extends ControllerBase {
  
  /**
   *
   * @var ManageEntittiesPluginManager
   */
  protected $ManageEntittiesPluginManager;
  
  /**
   *
   * @param ManageEntittiesPluginManager $ManageEntittiesPluginManager
   */
  function __construct(ManageEntittiesPluginManager $ManageEntittiesPluginManager) {
    $this->ManageEntittiesPluginManager = $ManageEntittiesPluginManager;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('plugin.manager.manage_entitties'));
  }
  
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
    $this->ManageEntittiesPluginManager->BuildCollectionsOfEnttities($plugin_id, $datas);
    return $datas;
  }
  
  /**
   * Permet de charger la configuration avancée
   *
   * @param string $plugin_id
   * @param string $entity_type_id
   */
  public function AdvanceManageEntities($plugin_id, $entity_type, $bundle) {
    $datas = [];
    $this->ManageEntittiesPluginManager->BuildAdvanceCollectionOfEnttities($plugin_id, $entity_type, $bundle, $datas);
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

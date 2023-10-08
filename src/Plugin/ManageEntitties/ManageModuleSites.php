<?php

namespace Drupal\manage_module_config\Plugin\ManageEntitties;

use Drupal\manage_module_config\ManageEntitties\ManageEntittiesPluginBase;
use Drupal\lesroidelareno\lesroidelareno;
use Drupal\Core\Url;
use Drupal\Core\Datetime\DateFormatter;

/**
 * Plugin implementation of the manage_entitties.
 *
 * @ManageEntitties(
 *   id = "manage_module_sites",
 *   label = @Translation("Manage Module sites"),
 *   description = @Translation("Foo description."),
 *   entities = {
 *     "domain_ovh_entity"
 *   }
 * )
 */
class ManageModuleSites extends ManageEntittiesPluginBase {
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntitties\ManageEntittiesInterface::GetName()
   */
  public function GetName() {
    return $this->configuration['name'];
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntitties\ManageEntittiesInterface::buildCollections()
   */
  public function buildCollections(array &$datas) {
    $definitions = $this->getPluginDefinition();
    /**
     *
     * @var DateFormatter $formatterDate
     */
    $formatterDate = \Drupal::service("date.formatter");
    if ($definitions['entities']) {
      foreach ($definitions['entities'] as $entity_type_id) {
        /**
         * On utilise list builder, en se basant sur les fonctions specifique
         * qu'on a ajouté.
         *
         * @var \Drupal\Core\Entity\EntityListBuilderInterface $ListBuilder
         */
        // $ListBuilder =
        // \Drupal::entityTypeManager()->getListBuilder($entity_type_id);
        // // apply custom filter
        // $datas[] = $ListBuilder->render();
        $query = \Drupal::entityTypeManager()->getStorage($entity_type_id)->getQuery();
        $query->condition('user_id', \Drupal::currentUser()->id());
        $query->accessCheck(TRUE);
        $query->sort('created', 'DESC');
        $ids = $query->execute();
        if ($ids) {
          $build['header'] = [
            '#type' => 'html_tag',
            '#tag' => 'h2',
            '#value' => 'Domaines generés'
          ];
          $header = [
            'id' => '#id',
            'name' => 'Sous domaine',
            'domaine' => 'domaine',
            'created' => 'Date creation',
            'operations' => 'operations'
          ];
          $rows = [];
          $entities = \Drupal::entityTypeManager()->getStorage($entity_type_id)->loadMultiple($ids);
          // dump($entities);
          foreach ($entities as $entity) {
            /**
             *
             * @var \Drupal\ovh_api_rest\Entity\DomainOvhEntity $entity
             */
            $id = $entity->id();
            $link = '';
            /**
             *
             * @var \Drupal\domain\Entity\Domain $domain
             */
            $domain = \Drupal::entityTypeManager()->getStorage("domain")->load($entity->getDomainIdDrupal());
            if ($domain) {
              $link = [
                '#type' => 'html_tag',
                '#tag' => 'a',
                '#weight' => 10,
                '#attributes' => [
                  'href' => $domain->getScheme() . $domain->getHostname(),
                  'target' => '_blanck'
                ],
                '#value' => $domain->getHostname()
              ];
            }
            // dump($link);
            $rows[$id] = [
              'id' => $id,
              'name' => $entity->getsubDomain(),
              // 'user' => $entity->getOwner()->getDisplayName(),
              'domaine' => [
                'data' => $link
              ],
              'created' => $formatterDate->format($entity->getCreatedTime()),
              'operations' => [
                'data' => $this->buildOperations($entity)
              ]
            ];
          }
          if ($rows) {
            $build['table'] = [
              '#type' => 'table',
              '#header' => $header,
              '#title' => 'Titre de la table',
              '#rows' => $rows,
              '#empty' => 'Aucun contenu'
              // '#cache' => [
              // 'contexts' => $this->entityType->getListCacheContexts(),
              // 'tags' => $this->entityType->getListCacheTags(),
              // ],
            ];
            $build['pager'] = [
              '#type' => 'pager'
            ];
            $datas[] = $build;
          }
        }
      }
    }
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntitties\ManageEntittiesInterface::getBaseRoute()
   */
  public function getBaseRoute() {
    /**
     *
     * @var \Drupal\Core\Http\RequestStack $RequestStack
     */
    $RequestStack = \Drupal::service('request_stack');
    $Request = $RequestStack->getCurrentRequest();
    return Url::fromRoute('manage_module_config.manage_entities', [
      'plugin_id' => $this->pluginId
    ], [
      'query' => [
        'destination' => $Request->getPathInfo()
      ]
    ]);
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntitties\ManageEntittiesInterface::getNumbers()
   */
  public function getNumbers() {
    /**
     *
     * @var \Drupal\managepackvhsost\Services\BlocksDomains $blocksdomains
     */
    $blocksdomains = \Drupal::service('managepackvhsost.blocksdomains');
    return $blocksdomains->getNumber();
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntitties\ManageEntittiesInterface::getDescription()
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
      'name' => 'Sites web',
      'description' => "Sites web generer",
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
      <path d="M501.1 395.7L384 278.6c-23.1-23.1-57.6-27.6-85.4-13.9L192 158.1V96L64 0 0 64l96 128h62.1l106.6 106.6c-13.6 27.8-9.2 62.3 13.9 85.4l117.1 117.1c14.6 14.6 38.2 14.6 52.7 0l52.7-52.7c14.5-14.6 14.5-38.2 0-52.7zM331.7 225c28.3 0 54.9 11 74.9 31l19.4 19.4c15.8-6.9 30.8-16.5 43.8-29.5 37.1-37.1 49.7-89.3 37.9-136.7-2.2-9-13.5-12.1-20.1-5.5l-74.4 74.4-67.9-11.3L334 98.9l74.4-74.4c6.6-6.6 3.4-17.9-5.7-20.2-47.4-11.7-99.6.9-136.6 37.9-28.5 28.5-41.9 66.1-41.2 103.6l82.1 82.1c8.1-1.9 16.5-2.9 24.7-2.9zm-103.9 82l-56.7-56.7L18.7 402.8c-25 25-25 65.5 0 90.5s65.5 25 90.5 0l123.6-123.6c-7.6-19.9-9.9-41.6-5-62.7zM64 472c-13.2 0-24-10.8-24-24 0-13.3 10.7-24 24-24s24 10.7 24 24c0 13.2-10.7 24-24 24z"/></svg>',
      'icon_svg_class' => 'btn-circle btn-secondary text-white btn-lg',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
  
}

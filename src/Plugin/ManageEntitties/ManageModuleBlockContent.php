<?php

namespace Drupal\manage_module_config\Plugin\ManageEntitties;

use Drupal\manage_module_config\ManageEntitties\ManageEntittiesPluginBase;
use Drupal\lesroidelareno\lesroidelareno;
use Drupal\Core\Url;

/**
 * Plugin implementation of the manage_entitties.
 *
 * @ManageEntitties(
 *   id = "manage_module_block_content",
 *   label = @Translation("Manage Module Commerce product"),
 *   description = @Translation("Foo description."),
 *   entities = {
 *     "block_content"
 *   },
 * )
 */
class ManageModuleBlockContent extends ManageEntittiesPluginBase {
  
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
    $domainAccessField = \Drupal\domain_access\DomainAccessManagerInterface::DOMAIN_ACCESS_FIELD;
    $definitions = $this->getPluginDefinition();
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
        $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
        $query->accessCheck(TRUE);
        $query->pager(10);
        $ids = $query->execute();
        if ($ids) {
          $header = [
            'id' => '#id',
            'name' => 'Titre',
            // 'user' => 'Auteur',
            'operations' => 'operations'
          ];
          $rows = [];
          $entities = \Drupal::entityTypeManager()->getStorage($entity_type_id)->loadMultiple($ids);
          // dump($entities);
          foreach ($entities as $entity) {
            /**
             *
             * @var \Drupal\blockscontent\Entity\BlocksContents $entity
             */
            $id = $entity->id();
            $rows[$id] = [
              'id' => $id,
              'name' => $entity->hasLinkTemplate('canonical') ? [
                'data' => [
                  '#type' => 'link',
                  '#title' => $entity->label(),
                  '#weight' => 10,
                  '#url' => $entity->toUrl('canonical')
                ]
              ] : $entity->label(),
              // 'user' => $entity->getOwner()->getDisplayName(),
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
    $definitions = $this->getPluginDefinition();
    $numbers = 0;
    if ($definitions['entities']) {
      $domainAccessField = \Drupal\domain_access\DomainAccessManagerInterface::DOMAIN_ACCESS_FIELD;
      foreach ($definitions['entities'] as $entity_type_id) {
        $query = \Drupal::entityTypeManager()->getStorage($entity_type_id)->getQuery();
        $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
        $numbers += $query->count()->execute();
      }
    }
    return $numbers;
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
      'name' => 'Blocks et sections',
      'description' => "Gerer les contenus statiques",
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
        <path d="M552 64H112c-20.858 0-38.643 13.377-45.248 32H24c-13.255 0-24 10.745-24 24v272c0 30.928 25.072 56 56 56h496c13.255 0 24-10.745 24-24V88c0-13.255-10.745-24-24-24zM48 392V144h16v248c0 4.411-3.589 8-8 8s-8-3.589-8-8zm480 8H111.422c.374-2.614.578-5.283.578-8V112h416v288zM172 280h136c6.627 0 12-5.373 12-12v-96c0-6.627-5.373-12-12-12H172c-6.627 0-12 5.373-12 12v96c0 6.627 5.373 12 12 12zm28-80h80v40h-80v-40zm-40 140v-24c0-6.627 5.373-12 12-12h136c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H172c-6.627 0-12-5.373-12-12zm192 0v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0-144v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0 72v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12z"/></svg>',
      'icon_svg_class' => 'btn-circle btn-wbu-secondary text-white btn-lg',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
  
}

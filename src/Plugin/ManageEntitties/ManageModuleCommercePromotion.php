<?php

namespace Drupal\manage_module_config\Plugin\ManageEntitties;

use Drupal\manage_module_config\ManageEntitties\ManageEntittiesPluginBase;
use Drupal\lesroidelareno\lesroidelareno;
use Drupal\Core\Url;

/**
 * Plugin implementation of the manage_entitties.
 *
 * @ManageEntitties(
 *   id = "manage_module_comerce_promotion",
 *   label = @Translation("Manage Module Commerce promotion"),
 *   description = @Translation("Foo description."),
 *   entities = {
 *     "commerce_promotion"
 *   },
 * )
 */
class ManageModuleCommercePromotion extends ManageEntittiesPluginBase {
  
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
    $entities = [];
    if ($definitions['entities']) {
      foreach ($definitions['entities'] as $entity_type_id) {
        $entityStorage = \Drupal::entityTypeManager()->getStorage($entity_type_id);
        /**
         *
         * @var \Drupal\Core\Entity\ContentEntityType $entityType
         */
        $entityType = $entityStorage->getEntityType();
        if ($entityStorage) {
          $query = $entityStorage->getQuery();
          $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
          $query->accessCheck(TRUE);
          $query->sort('created', 'DESC');
          $query->pager(10);
          $ids = $query->execute();
          if ($ids) {
            $entities = $entityStorage->loadMultiple($ids);
            $this->getEntities($entities, $entityType, $entity_type_id, $datas);
          }
          else {
            $this->getEntities($entities, $entityType, $entity_type_id, $datas);
          }
        }
      }
    }
  }
  
  protected function getEntities($entities, $entityType, $entity_type_id, &$datas) {
    $rows = [];
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
        'user' => $entity->getOwner()->getDisplayName(),
        'operations' => [
          'data' => $this->buildOperations($entity)
        ]
      ];
    }
    
    $header = [
      'id' => '#id',
      'name' => 'Titre',
      'user' => 'Auteur',
      'operations' => 'operations'
    ];
    $build['header'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $entityType->getLabel()
    ];
    $route = 'entity.' . $entity_type_id . '.add_form';
    $build['add_new'] = [
      '#type' => 'link',
      '#title' => ' + Ajouter une ' . $entityType->getLabel(),
      '#url' => Url::fromRoute($route)
    ];
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
    $link = 'internal:/manage-' . $entity_type_id . '/' . $entityType->id();
    $urlDetail = \Drupal\Core\Url::fromUri($link, [
      'query' => [
        'destination' => $this->getPathInfo()
      ]
    ]);
    $build['datails'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'page-content'
        ]
      ],
      [
        '#type' => 'link',
        '#title' => 'Plus de details',
        '#url' => $urlDetail,
        '#options' => [
          'attributes' => [
            'class' => [
              'button'
            ]
          ]
        ]
      ]
    ];
    $datas[] = $build;
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
      'name' => 'Promotions',
      'description' => "Gerer vos promotions et coupons",
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z"/></svg>',
      'icon_svg_class' => 'btn-circle btn-wbu-secondary text-white btn-lg',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
  
}

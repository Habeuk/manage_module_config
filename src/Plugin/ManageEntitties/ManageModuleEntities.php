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
 *   id = "manage_module_entities",
 *   label = @Translation("Manage Module Entities"),
 *   description = @Translation("Foo description."),
 *   entities = {
 *     "blocks_contents",
 *     "node",
 *     "site_internet_entity"
 *   },
 * )
 */
class ManageModuleEntities extends ManageEntittiesPluginBase {
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntitties\ManageEntittiesInterface::GetName()
   */
  public function GetName() {
    return $this->configuration['name'];
  }
  
  /**
   * Permet de construire un rendu advancé avec des recherches et des filtres.
   * Si possible avec une option en ajax. (plus tard).
   *
   * @param string $entity_type_id
   * @param array $datas
   */
  public function buildadvanceCollection(string $entity_type_id, $bundle, array &$datas) {
    $definitions = $this->getPluginDefinition();
    $domainAccessField = \Drupal\domain_access\DomainAccessManagerInterface::DOMAIN_ACCESS_FIELD;
    if ($definitions['entities'] && in_array($entity_type_id, $definitions['entities'])) {
      $query = \Drupal::entityTypeManager()->getStorage($entity_type_id)->getQuery();
      $query->accessCheck(TRUE);
      $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
      $query->condition('type', $bundle);
      $query->sort('created', 'DESC');
      $query->pager(10);
      $ids = $query->execute();
      if ($ids) {
        /**
         * Build form search
         */
        $form = \Drupal::formBuilder()->getForm('Drupal\manage_module_config\Form\EntitiesFilter');
        $datas[] = $form;
        
        /**
         *
         * @var DateFormatter $formatterDate
         */
        $formatterDate = \Drupal::service("date.formatter");
        $header = [
          'id' => '#id',
          'name' => 'Titre',
          'user' => 'Auteur',
          'created' => 'Date creation',
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
            'user' => $entity->getOwner()->getDisplayName(),
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
            '#empty' => 'Aucun contenu',
            '#attributes' => [
              'class' => [
                'page-content00'
              ]
            ]
          ];
          $build['pager'] = [
            '#type' => 'pager'
          ];
          $datas[] = $build;
        }
      }
    }
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\manage_module_config\ManageEntitties\ManageEntittiesInterface::buildCollections()
   */
  public function buildCollections(array &$datas) {
    $definitions = $this->getPluginDefinition();
    
    if ($definitions['entities']) {
      foreach ($definitions['entities'] as $entity_type_id) {
        $this->getEntities($entity_type_id, $datas);
      }
    }
  }
  
  protected function getEntities($entity_type_id, &$datas) {
    $domainAccessField = \Drupal\domain_access\DomainAccessManagerInterface::DOMAIN_ACCESS_FIELD;
    $entity = \Drupal::entityTypeManager()->getStorage($entity_type_id);
    $entity_bundle_id = $entity->getEntityType()->getBundleEntityType();
    $entitiesType = \Drupal::entityTypeManager()->getStorage($entity_bundle_id)->loadMultiple();
    foreach ($entitiesType as $entityType) {
      $query = \Drupal::entityTypeManager()->getStorage($entity_type_id)->getQuery();
      $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
      $query->condition('type', $entityType->id());
      $query->accessCheck(TRUE);
      $query->sort('created', 'DESC');
      $query->pager(10);
      $ids = $query->execute();
      if ($ids) {
        $this->buildEntitiesTypeTables($ids, $entityType, $entity_type_id, $entity_bundle_id, $datas);
      }
    }
  }
  
  /**
   * --
   */
  protected function buildEntitiesTypeTables(array $ids, $entityType, $entity_type_id, $entity_bundle_id, array &$datas) {
    /**
     *
     * @var DateFormatter $formatterDate
     */
    $formatterDate = \Drupal::service("date.formatter");
    $build['header'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $entityType->label()
    ];
    // Add new content
    if ($entity_type_id == 'node') {
      $route = 'node.add';
    }
    else
      $route = 'entity.' . $entity_type_id . '.add_form';
    $build['add_new'] = [
      '#type' => 'link',
      '#title' => ' + Ajouter',
      '#url' => Url::fromRoute($route, [
        $entity_bundle_id => $entityType->id()
      ])
    ];
    $header = [
      'id' => '#id',
      'name' => 'Titre',
      'user' => 'Auteur',
      'created' => 'Date creation',
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
        'user' => $entity->getOwner()->getDisplayName(),
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
        '#empty' => 'Aucun contenu',
        '#attributes' => [
          'class' => [
            'page-content00'
          ]
        ]
        
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
      // $urlDetail =
      // Url::fromRoute('manage_module_config.manage_entities.entity_type', [
      // 'plugin_id' => $this->pluginId,
      // 'entity_type' => $entity_type_id,
      // 'bundle' => $entityType->id()
      // ]);
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
      'name' => 'Contenus et pages',
      'description' => "Gerer les pages, les articles ...",
      'icon_svg_class' => 'btn-circle btn-primary text-white btn-lg',
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
        <path d="M552 64H112c-20.858 0-38.643 13.377-45.248 32H24c-13.255 0-24 10.745-24 24v272c0 30.928 25.072 56 56 56h496c13.255 0 24-10.745 24-24V88c0-13.255-10.745-24-24-24zM48 392V144h16v248c0 4.411-3.589 8-8 8s-8-3.589-8-8zm480 8H111.422c.374-2.614.578-5.283.578-8V112h416v288zM172 280h136c6.627 0 12-5.373 12-12v-96c0-6.627-5.373-12-12-12H172c-6.627 0-12 5.373-12 12v96c0 6.627 5.373 12 12 12zm28-80h80v40h-80v-40zm-40 140v-24c0-6.627 5.373-12 12-12h136c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H172c-6.627 0-12-5.373-12-12zm192 0v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0-144v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0 72v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12z"/></svg>',
      'enable' => true
    ] + parent::defaultConfiguration();
  }
  
}

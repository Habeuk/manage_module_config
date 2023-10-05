<?php

namespace Drupal\manage_module_config\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\lesroidelareno\lesroidelareno;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Liste les configuration accessible.
 *
 * @Block(
 *   id = "manage_module_config_entities_lists_block",
 *   admin_label = @Translation("Contenus"),
 *   category = @Translation("manage module config")
 * )
 */
class EntitiesListsBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  
  function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('entity_type.manager'));
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $configs = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'row',
          'row-marg-2px',
          'mb-5'
        ]
      ]
    ];
    $this->loadAllProducts($configs);
    $this->loadAllentities($configs);
    $this->loadAllBlocks($configs);
    $build['content']['container'] = $configs;
    return $build;
  }
  
  /**
   * Charge toue le configurationa active
   */
  protected function loadAllentities(&$configs) {
    $domainAccessField = \Drupal\domain_access\DomainAccessManagerInterface::DOMAIN_ACCESS_FIELD;
    $entities_type = [
      'blocks_contents',
      'node',
      'site_internet_entity'
    ];
    $numbers = 0;
    foreach ($entities_type as $entity_type_id) {
      $query = $this->entityTypeManager->getStorage($entity_type_id)->getQuery();
      $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
      $numbers += $query->count()->execute();
    }
    if ($numbers > 0)
      $configs[] = [
        '#theme' => 'manage_module_config_card_info',
        '#name' => 'Contenus et pages',
        '#description' => 'Gerer les pages, les articles',
        '#icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M552 64H112c-20.858 0-38.643 13.377-45.248 32H24c-13.255 0-24 10.745-24 24v272c0 30.928 25.072 56 56 56h496c13.255 0 24-10.745 24-24V88c0-13.255-10.745-24-24-24zM48 392V144h16v248c0 4.411-3.589 8-8 8s-8-3.589-8-8zm480 8H111.422c.374-2.614.578-5.283.578-8V112h416v288zM172 280h136c6.627 0 12-5.373 12-12v-96c0-6.627-5.373-12-12-12H172c-6.627 0-12 5.373-12 12v96c0 6.627 5.373 12 12 12zm28-80h80v40h-80v-40zm-40 140v-24c0-6.627 5.373-12 12-12h136c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H172c-6.627 0-12-5.373-12-12zm192 0v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0-144v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0 72v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12z"/></svg>',
        '#icon_svg_class' => 'btn-circle btn-primary text-white btn-lg',
        '#route' => null,
        '#number' => $numbers
      ];
    return $configs;
  }
  
  /**
   * Charge toue le configurationa active
   */
  protected function loadAllBlocks(&$configs) {
    $domainAccessField = \Drupal\domain_access\DomainAccessManagerInterface::DOMAIN_ACCESS_FIELD;
    $entities_type = [
      'block_content'
    ];
    $numbers = 0;
    foreach ($entities_type as $entity_type_id) {
      $query = $this->entityTypeManager->getStorage($entity_type_id)->getQuery();
      $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
      $numbers += $query->count()->execute();
    }
    if ($numbers > 0)
      $configs[] = [
        '#theme' => 'manage_module_config_card_info',
        '#name' => 'Blocs et sections',
        '#description' => 'Gerer les contenus statique',
        '#icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M552 64H112c-20.858 0-38.643 13.377-45.248 32H24c-13.255 0-24 10.745-24 24v272c0 30.928 25.072 56 56 56h496c13.255 0 24-10.745 24-24V88c0-13.255-10.745-24-24-24zM48 392V144h16v248c0 4.411-3.589 8-8 8s-8-3.589-8-8zm480 8H111.422c.374-2.614.578-5.283.578-8V112h416v288zM172 280h136c6.627 0 12-5.373 12-12v-96c0-6.627-5.373-12-12-12H172c-6.627 0-12 5.373-12 12v96c0 6.627 5.373 12 12 12zm28-80h80v40h-80v-40zm-40 140v-24c0-6.627 5.373-12 12-12h136c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H172c-6.627 0-12-5.373-12-12zm192 0v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0-144v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0 72v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12z"/></svg>',
        '#icon_svg_class' => 'btn-circle btn-info text-white btn-lg',
        '#route' => null,
        '#number' => $numbers
      ];
    
    return $configs;
  }
  
  /**
   * Charge tous les produits
   */
  protected function loadAllProducts(&$configs) {
    $domainAccessField = \Drupal\domain_access\DomainAccessManagerInterface::DOMAIN_ACCESS_FIELD;
    $entities_type = [
      'commerce_product'
    ];
    $numbers = 0;
    foreach ($entities_type as $entity_type_id) {
      $query = $this->entityTypeManager->getStorage($entity_type_id)->getQuery();
      $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
      $numbers += $query->count()->execute();
    }
    if ($numbers > 0) {
      $configs[] = [
        '#theme' => 'manage_module_config_card_info',
        '#name' => 'Produits et services',
        '#description' => 'Gerer vos produits et services',
        '#icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M552 64H112c-20.858 0-38.643 13.377-45.248 32H24c-13.255 0-24 10.745-24 24v272c0 30.928 25.072 56 56 56h496c13.255 0 24-10.745 24-24V88c0-13.255-10.745-24-24-24zM48 392V144h16v248c0 4.411-3.589 8-8 8s-8-3.589-8-8zm480 8H111.422c.374-2.614.578-5.283.578-8V112h416v288zM172 280h136c6.627 0 12-5.373 12-12v-96c0-6.627-5.373-12-12-12H172c-6.627 0-12 5.373-12 12v96c0 6.627 5.373 12 12 12zm28-80h80v40h-80v-40zm-40 140v-24c0-6.627 5.373-12 12-12h136c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H172c-6.627 0-12-5.373-12-12zm192 0v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0-144v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0 72v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12z"/></svg>',
        '#icon_svg_class' => 'btn-circle btn-wbu-secondary text-white btn-lg',
        '#route' => null,
        '#number' => $numbers
      ];
      // On ajoute egalement les commandes.
      $entities_type = [
        'commerce_order'
      ];
      $numbers = 0;
      foreach ($entities_type as $entity_type_id) {
        $query = $this->entityTypeManager->getStorage($entity_type_id)->getQuery();
        $query->condition($domainAccessField, lesroidelareno::getCurrentDomainId());
        $numbers += $query->count()->execute();
      }
      
      $configs[] = [
        '#theme' => 'manage_module_config_card_info',
        '#name' => 'Commandes',
        '#description' => 'Traiter vos commandes',
        '#icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M552 64H112c-20.858 0-38.643 13.377-45.248 32H24c-13.255 0-24 10.745-24 24v272c0 30.928 25.072 56 56 56h496c13.255 0 24-10.745 24-24V88c0-13.255-10.745-24-24-24zM48 392V144h16v248c0 4.411-3.589 8-8 8s-8-3.589-8-8zm480 8H111.422c.374-2.614.578-5.283.578-8V112h416v288zM172 280h136c6.627 0 12-5.373 12-12v-96c0-6.627-5.373-12-12-12H172c-6.627 0-12 5.373-12 12v96c0 6.627 5.373 12 12 12zm28-80h80v40h-80v-40zm-40 140v-24c0-6.627 5.373-12 12-12h136c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H172c-6.627 0-12-5.373-12-12zm192 0v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0-144v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12zm0 72v-24c0-6.627 5.373-12 12-12h104c6.627 0 12 5.373 12 12v24c0 6.627-5.373 12-12 12H364c-6.627 0-12-5.373-12-12z"/></svg>',
        '#icon_svg_class' => 'btn-circle btn-wbu-thirdly text-white btn-lg',
        '#route' => null,
        '#number' => $numbers
      ];
    }
    
    return $configs;
  }
  
}

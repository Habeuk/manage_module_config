<?php

namespace Drupal\manage_module_config\ManageEntitties;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Url;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Routing\RedirectDestinationTrait;

/**
 * Base class for manage_entitties plugins.
 */
abstract class ManageEntittiesPluginBase extends PluginBase implements ManageEntittiesInterface {
  use StringTranslationTrait;
  use RedirectDestinationTrait;
  
  /**
   *
   * @var string
   */
  protected $getPathInfo = NULL;
  
  /**
   *
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }
  
  /**
   *
   * @return array
   */
  public function defaultConfiguration() {
    return [
      'name' => 'Menu',
      'description' => "Permet gerer les elements du menu ",
      // 'route' => NULL,
      'weight' => 100, // [0-100]
      'icon_svg_class' => 'btn-danger text-white btn-lg',
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
						<path d="M80 368H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm0-320H16A16 16 0 0 0 0 64v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16V64a16 16 0 0 0-16-16zm0 160H16a16 16 0 0 0-16 16v64a16 16 0 0 0 16 16h64a16 16 0 0 0 16-16v-64a16 16 0 0 0-16-16zm416 176H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"/>
					</svg>',
      'enable' => false // La configuration ne serra pas charger par defaut.
    ];
  }
  
  public function getIconSvg() {
    return $this->configuration['icon_svg'];
  }
  
  public function getIconSvgClass() {
    return $this->configuration['icon_svg_class'];
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\Component\Plugin\ConfigurableInterface::getConfiguration()
   */
  public function getConfiguration() {
    return $this->configuration;
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\Component\Plugin\ConfigurableInterface::setConfiguration()
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = $configuration;
  }
  
  /**
   * Builds a renderable list of operation links for the entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *        The entity on which the linked operations will be performed.
   *        
   * @return array A renderable array of operation links.
   *        
   * @see \Drupal\Core\Entity\EntityListBuilder::buildRow()
   */
  public function buildOperations(ContentEntityInterface $entity) {
    $build = [
      '#type' => 'operations',
      '#links' => $this->getOperations($entity)
    ];
    
    return $build;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function getOperations(ContentEntityInterface $entity) {
    $operations = [];
    if ($entity->access('update') && $entity->hasLinkTemplate('edit-form')) {
      $operations['edit'] = [
        'title' => $this->t('Edit'),
        'weight' => 10,
        'url' => $this->ensureDestination($entity->toUrl('edit-form'))
      ];
    }
    if ($entity->access('delete') && $entity->hasLinkTemplate('delete-form')) {
      $operations['delete'] = [
        'title' => $this->t('Delete'),
        'weight' => 100,
        'url' => $this->ensureDestination($entity->toUrl('delete-form'))
      ];
    }
    return $operations;
  }
  
  /**
   * Ensures that a destination is present on the given URL.
   *
   * @param \Drupal\Core\Url $url
   *        The URL object to which the destination should be added.
   *        
   * @return \Drupal\Core\Url The updated URL object.
   */
  protected function ensureDestination(Url $url) {
    return $url->mergeOptions([
      'query' => [
        'destination' => $this->getPathInfo()
      ]
    ]);
  }
  
  /**
   * --
   */
  protected function getPathInfo() {
    if (!$this->getPathInfo) {
      /**
       *
       * @var \Drupal\Core\Http\RequestStack $RequestStack
       */
      $RequestStack = \Drupal::service('request_stack');
      $Request = $RequestStack->getCurrentRequest();
      $this->getPathInfo = $Request->getPathInfo();
    }
    return $this->getPathInfo;
  }
  
}

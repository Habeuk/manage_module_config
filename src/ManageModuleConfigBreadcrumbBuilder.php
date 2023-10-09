<?php

namespace Drupal\manage_module_config;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\node\NodeInterface;

/**
 * Provides a breadcrumb builder for articles.
 */
class ManageModuleConfigBreadcrumbBuilder implements BreadcrumbBuilderInterface {
  
  use StringTranslationTrait;
  
  /**
   *
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    $routes = [
      "lesroidelareno.manage_menu",
      "manage_module_config.manage_entities",
      "generate_style_theme.managecustom.styles",
      "entity.config_theme_entity.edit_form"
    ];
    $routeName = $route_match->getRouteName();
    if (in_array($routeName, $routes) || str_contains($routeName, 'bookingsystem')) {
      return true;
    }
    return false;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();
    //
    $links[] = Link::createFromRoute($this->t('Home'), '<front>');
    // Articles page is a view.
    $links[] = Link::createFromRoute($this->t('Dashbord'), 'user.page');
    //
    $breadcrumb->setLinks($links);
    //
    return $breadcrumb;
  }
  
}

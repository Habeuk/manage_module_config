<?php
declare(strict_types = 1);

namespace Drupal\manage_module_config\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Stephane888\Debug\Repositories\ConfigDrupal;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\domain\DomainNegotiator;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Configure manage module config settings for this site.
 */
final class WebformUsers extends ConfigFormBase {
  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  
  /**
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;
  
  /**
   *
   * @var \Drupal\domain\DomainNegotiator
   */
  protected $DomainNegotiator;
  const key_name = "manage_module_config.webformsusers";
  
  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *        The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $EntityTypeManagerInterface, RequestStack $RequestStack, DomainNegotiator $DomainNegotiator) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $EntityTypeManagerInterface;
    $this->request = $RequestStack->getCurrentRequest();
    $this->DomainNegotiator = $DomainNegotiator;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('config.factory'), $container->get('entity_type.manager'), $container->get('request_stack'), $container->get('domain.negotiator'));
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'manage_module_config_webform_users';
  }
  
  /**
   *
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      self::key_name
    ];
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $query = $this->request->query->get('domain_config_ui_domain');
    $domain = $this->DomainNegotiator->getActiveDomain();
    if (empty($query)) {
      if ($domain) {
        $url = Url::fromRoute("manage_module_config.webform_users", [], [
          'query' => [
            'domain_config_ui_domain' => $domain->id(),
            'domain_config_ui_language' => ''
          ],
          'absolute' => TRUE
        ]);
        return new RedirectResponse($url->toString());
      }
    }
    elseif ($domain->id() !== $query) {
      /**
       *
       * @var \Drupal\domain\Entity\Domain $domain2
       */
      $domain2 = $this->entityTypeManager->getStorage('domain')->load($query);
      // On redirige sur le domaine definie dans la variable, car on ne peut pas
      // afficher ou editer une valeur à partir d'une autre domaine.
      if ($domain2) {
        $url = Url::fromRoute("manage_module_config.webform_users", [], [
          'query' => [
            'domain_config_ui_domain' => $domain2->id(),
            'domain_config_ui_language' => ''
          ],
          'absolute' => false
        ]);
        $url_string = $domain2->getScheme() . $domain2->getHostname() . $url->toString();
        return new RedirectResponse($url_string);
      }
    }
    $configs = ConfigDrupal::config(self::key_name);
    $form['webforms_users'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Selectioner les fomulaires permettant de recuperer les données aux utilisateurs'),
      '#default_value' => isset($configs['webforms_users']) ? $configs['webforms_users'] : [],
      '#options' => \Drupal\manage_module_config\ManageModuleConfig::getFormWebformByUser()
    ];
    return parent::buildForm($form, $form_state);
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $config = $this->config(self::key_name);
    $config->set('webforms_users', $form_state->getValue('webforms_users'));
    $config->save();
    parent::submitForm($form, $form_state);
  }
}

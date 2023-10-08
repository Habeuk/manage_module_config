<?php

namespace Drupal\manage_module_config\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure manage module config settings for this site.
 */
class SettingsForm extends ConfigFormBase {
  
  /**
   *
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'manage_module_config_settings';
  }
  
  /**
   *
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'manage_module_config.settings'
    ];
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $plugins = $this->config('manage_module_config.settings')->get('plugins');
    $form['plugins'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Selectionner les modules'),
      '#options' => \Drupal\manage_module_config\ManageModuleConfig::getPlugins(),
      '#default_value' => $plugins ? $plugins : []
    ];
    return parent::buildForm($form, $form_state);
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('manage_module_config.settings')->set('plugins', $form_state->getValue('plugins'))->save();
    parent::submitForm($form, $form_state);
  }
  
}

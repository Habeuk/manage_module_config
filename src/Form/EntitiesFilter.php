<?php

namespace Drupal\manage_module_config\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure manage module config settings for this site.
 *
 * @see https://drupal.stackexchange.com/questions/291752/how-can-i-create-a-get-form-with-the-form-api
 */
class EntitiesFilter extends FormBase {
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\Core\Form\FormInterface::getFormId()
   */
  public function getFormId() {
    // Unique ID of the form.
    return 'example_form';
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\Core\Form\FormInterface::buildForm()
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Create a $form API array.
    $form['phone_number'] = array(
      '#type' => 'tel',
      '#title' => $this->t('Your phone number')
    );
    $form['save'] = array(
      '#type' => 'submit',
      '#value' => $this->t('filter')
    );
    return $form;
  }
  
  /**
   *
   * {@inheritdoc}
   * @see \Drupal\Core\Form\FormInterface::submitForm()
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Handle submitted form data.
  }
  
}
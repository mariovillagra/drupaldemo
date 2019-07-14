<?php

namespace Drupal\smartphones\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Smartphones entity revision.
 *
 * @ingroup smartphones
 */
class SmartphonesEntityRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The Smartphones entity revision.
   *
   * @var \Drupal\smartphones\Entity\SmartphonesEntityInterface
   */
  protected $revision;

  /**
   * The Smartphones entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $SmartphonesEntityStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new SmartphonesEntityRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(EntityStorageInterface $entity_storage, Connection $connection) {
    $this->SmartphonesEntityStorage = $entity_storage;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_type_manager = $container->get('entity_type.manager');
    return new static(
      $entity_type_manager->getStorage('smartphones_entity'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'smartphones_entity_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => format_date($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.smartphones_entity.version_history', ['smartphones_entity' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $smartphones_entity_revision = NULL) {
    $this->revision = $this->SmartphonesEntityStorage->loadRevision($smartphones_entity_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->SmartphonesEntityStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Smartphones entity: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Smartphones entity %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.smartphones_entity.canonical',
       ['smartphones_entity' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {smartphones_entity_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.smartphones_entity.version_history',
         ['smartphones_entity' => $this->revision->id()]
      );
    }
  }

}

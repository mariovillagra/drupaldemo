<?php

namespace Drupal\smartphones;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\smartphones\Entity\SmartphonesEntityInterface;

/**
 * Defines the storage handler class for Smartphones entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Smartphones entity entities.
 *
 * @ingroup smartphones
 */
class SmartphonesEntityStorage extends SqlContentEntityStorage implements SmartphonesEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(SmartphonesEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {smartphones_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {smartphones_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(SmartphonesEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {smartphones_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('smartphones_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}

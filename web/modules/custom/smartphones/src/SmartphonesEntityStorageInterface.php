<?php

namespace Drupal\smartphones;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface SmartphonesEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Smartphones entity revision IDs for a specific Smartphones entity.
   *
   * @param \Drupal\smartphones\Entity\SmartphonesEntityInterface $entity
   *   The Smartphones entity entity.
   *
   * @return int[]
   *   Smartphones entity revision IDs (in ascending order).
   */
  public function revisionIds(SmartphonesEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Smartphones entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Smartphones entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\smartphones\Entity\SmartphonesEntityInterface $entity
   *   The Smartphones entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(SmartphonesEntityInterface $entity);

  /**
   * Unsets the language for all Smartphones entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}

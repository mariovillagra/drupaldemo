<?php

namespace Drupal\smartphones\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Smartphones entity entities.
 *
 * @ingroup smartphones
 */
interface SmartphonesEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Smartphones entity name.
   *
   * @return string
   *   Name of the Smartphones entity.
   */
  public function getName();

  /**
   * Sets the Smartphones entity name.
   *
   * @param string $name
   *   The Smartphones entity name.
   *
   * @return \Drupal\smartphones\Entity\SmartphonesEntityInterface
   *   The called Smartphones entity entity.
   */
  public function setName($name);

  /**
   * Gets the Smartphones entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Smartphones entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Smartphones entity creation timestamp.
   *
   * @param int $timestamp
   *   The Smartphones entity creation timestamp.
   *
   * @return \Drupal\smartphones\Entity\SmartphonesEntityInterface
   *   The called Smartphones entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Smartphones entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Smartphones entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\smartphones\Entity\SmartphonesEntityInterface
   *   The called Smartphones entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Smartphones entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Smartphones entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\smartphones\Entity\SmartphonesEntityInterface
   *   The called Smartphones entity entity.
   */
  public function setRevisionUserId($uid);

}

<?php

namespace Drupal\smartphones;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Smartphones entity entity.
 *
 * @see \Drupal\smartphones\Entity\SmartphonesEntity.
 */
class SmartphonesEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\smartphones\Entity\SmartphonesEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished smartphones entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published smartphones entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit smartphones entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete smartphones entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add smartphones entity entities');
  }

}

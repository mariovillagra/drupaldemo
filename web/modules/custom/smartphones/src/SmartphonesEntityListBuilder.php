<?php

namespace Drupal\smartphones;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Smartphones entity entities.
 *
 * @ingroup smartphones
 */
class SmartphonesEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Smartphones entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\smartphones\Entity\SmartphonesEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.smartphones_entity.edit_form',
      ['smartphones_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}

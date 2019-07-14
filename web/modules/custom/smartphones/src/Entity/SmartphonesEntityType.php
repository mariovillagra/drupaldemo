<?php

namespace Drupal\smartphones\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Smartphones entity type entity.
 *
 * @ConfigEntityType(
 *   id = "smartphones_entity_type",
 *   label = @Translation("Smartphones entity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\smartphones\SmartphonesEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\smartphones\Form\SmartphonesEntityTypeForm",
 *       "edit" = "Drupal\smartphones\Form\SmartphonesEntityTypeForm",
 *       "delete" = "Drupal\smartphones\Form\SmartphonesEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\smartphones\SmartphonesEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "smartphones_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "smartphones_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/smartphones_entity_type/{smartphones_entity_type}",
 *     "add-form" = "/admin/structure/smartphones_entity_type/add",
 *     "edit-form" = "/admin/structure/smartphones_entity_type/{smartphones_entity_type}/edit",
 *     "delete-form" = "/admin/structure/smartphones_entity_type/{smartphones_entity_type}/delete",
 *     "collection" = "/admin/structure/smartphones_entity_type"
 *   }
 * )
 */
class SmartphonesEntityType extends ConfigEntityBundleBase implements SmartphonesEntityTypeInterface {

  /**
   * The Smartphones entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Smartphones entity type label.
   *
   * @var string
   */
  protected $label;

}

<?php

namespace Drupal\moby_help_with_bundle\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Moby help with bundle entity entities.
 *
 * @ingroup moby_help_with_bundle
 */
interface MobyHelpWithBundleEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Moby help with bundle entity name.
   *
   * @return string
   *   Name of the Moby help with bundle entity.
   */
  public function getName();

  /**
   * Sets the Moby help with bundle entity name.
   *
   * @param string $name
   *   The Moby help with bundle entity name.
   *
   * @return \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface
   *   The called Moby help with bundle entity entity.
   */
  public function setName($name);

  /**
   * Gets the Moby help with bundle entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Moby help with bundle entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Moby help with bundle entity creation timestamp.
   *
   * @param int $timestamp
   *   The Moby help with bundle entity creation timestamp.
   *
   * @return \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface
   *   The called Moby help with bundle entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Moby help with bundle entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Moby help with bundle entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface
   *   The called Moby help with bundle entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Moby help with bundle entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Moby help with bundle entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface
   *   The called Moby help with bundle entity entity.
   */
  public function setRevisionUserId($uid);

}

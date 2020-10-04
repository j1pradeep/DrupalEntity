<?php

namespace Drupal\moby_help;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\moby_help\Entity\MobyHelpEntityInterface;

/**
 * Defines the storage handler class for Moby help entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Moby help entity entities.
 *
 * @ingroup moby_help
 */
interface MobyHelpEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Moby help entity revision IDs for a specific Moby help entity.
   *
   * @param \Drupal\moby_help\Entity\MobyHelpEntityInterface $entity
   *   The Moby help entity entity.
   *
   * @return int[]
   *   Moby help entity revision IDs (in ascending order).
   */
  public function revisionIds(MobyHelpEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Moby help entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Moby help entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\moby_help\Entity\MobyHelpEntityInterface $entity
   *   The Moby help entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(MobyHelpEntityInterface $entity);

  /**
   * Unsets the language for all Moby help entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}

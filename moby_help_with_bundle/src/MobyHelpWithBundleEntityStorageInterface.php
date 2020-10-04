<?php

namespace Drupal\moby_help_with_bundle;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface;

/**
 * Defines the storage handler class for Moby help with bundle entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Moby help with bundle entity entities.
 *
 * @ingroup moby_help_with_bundle
 */
interface MobyHelpWithBundleEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Moby help with bundle entity revision IDs for a specific Moby help with bundle entity.
   *
   * @param \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface $entity
   *   The Moby help with bundle entity entity.
   *
   * @return int[]
   *   Moby help with bundle entity revision IDs (in ascending order).
   */
  public function revisionIds(MobyHelpWithBundleEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Moby help with bundle entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Moby help with bundle entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\moby_help_with_bundle\Entity\MobyHelpWithBundleEntityInterface $entity
   *   The Moby help with bundle entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(MobyHelpWithBundleEntityInterface $entity);

  /**
   * Unsets the language for all Moby help with bundle entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}

<?php

namespace Drupal\moby_help_with_bundle;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class MobyHelpWithBundleEntityStorage extends SqlContentEntityStorage implements MobyHelpWithBundleEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(MobyHelpWithBundleEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {moby_help_with_bundle_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {moby_help_with_bundle_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(MobyHelpWithBundleEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {moby_help_with_bundle_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('moby_help_with_bundle_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}

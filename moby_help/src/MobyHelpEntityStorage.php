<?php

namespace Drupal\moby_help;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class MobyHelpEntityStorage extends SqlContentEntityStorage implements MobyHelpEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(MobyHelpEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {moby_help_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {moby_help_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(MobyHelpEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {moby_help_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('moby_help_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}

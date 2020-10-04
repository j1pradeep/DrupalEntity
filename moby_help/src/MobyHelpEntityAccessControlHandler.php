<?php

namespace Drupal\moby_help;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Moby help entity entity.
 *
 * @see \Drupal\moby_help\Entity\MobyHelpEntity.
 */
class MobyHelpEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\moby_help\Entity\MobyHelpEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished moby help entity entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published moby help entity entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit moby help entity entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete moby help entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add moby help entity entities');
  }


}

<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

final class ApiLoginChecker implements UserCheckerInterface
{
    /**
     * Perform pre-authentication checks on the user.
     *
     * @param object $user The user object
     *
     * @return void
     */
    public function checkPreAuth(object $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->getApiEnabled()) {
            throw new CustomUserMessageAccountStatusException(
                message: 'Votre compte ne dispose pas de l’accès à l’API.',
                code: 403,
            );
        }
    }

    /**
     * Perform post-authentication checks on the user.
     *
     * @param object $user The user object
     *
     * @return void
     */
    public function checkPostAuth(object $user): void
    {
        // No post-auth checks needed for API login
    }
}

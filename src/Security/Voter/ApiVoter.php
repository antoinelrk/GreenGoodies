<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ApiVoter extends Voter
{
    /**
     * The attribute to check for API access.
     * @var string
     */
    public const string ACCESS = 'API_ACCESS';

    /**
     * Determine if the voter supports the given attribute and subject.
     *
     * @param string $attribute
     * @param mixed $subject
     *
     * @return bool
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::ACCESS && $subject === null;
    }

    /**
     * Vote on the given attribute for the current user.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!\is_object($user)) {
            return false;
        }

        // ByPass admin
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) return true;

        return (bool) ($user->getApiEnabled() ?? false);
    }
}

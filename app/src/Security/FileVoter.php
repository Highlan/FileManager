<?php

namespace App\Security;


use App\Entity\File;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FileVoter extends Voter
{
    const DOWNLOAD = 'download';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DOWNLOAD])) {
            return false;
        }

        // only vote on File objects
        if (!$subject instanceof File) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var File $file */
        $file = $subject;

        switch ($attribute) {
            case self::DOWNLOAD:
                return $this->canDownload($file, $user);
                break;
            default:
                return false;
        }
    }

    private function canDownload(File $file, User $user)
    {
        return $user === $file->getOwner();
    }
}
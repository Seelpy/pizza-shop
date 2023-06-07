<?php
declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function loadUserByIdentifier(string $username)
    {
        $user = $this->repository->findByEmail($username);
        if ($user === null)
        {
            throw new UserNotFoundException($username);
        }
        return new SecurityUser($user->getId(), $user->getEmail(), $user->getPassword(), $user->getRole());
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof SecurityUser)
        {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        $currentUser = $this->repository->findByEmail($user->getUserIdentifier());
        if ($currentUser === null)
        {
            throw new UserNotFoundException($user->getUserIdentifier());
        }
        return new SecurityUser($user->getId(), $currentUser->getEmail(), $currentUser->getPassword(), $currentUser->getRole());
    }

    public function supportsClass(string $class)
    {
        return SecurityUser::class === $class || is_subclass_of($class, SecurityUser::class);
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {}
}
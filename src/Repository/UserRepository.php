<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    private EntityManagerInterface $entityManeger;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManeger = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function findById(int $id): ?User 
    {
        return $this->repository->findOneBy(["id" => $id]);
    }

    public function findByEmail(string $email): ?User 
    {
        return $this->repository->findOneBy(["email" =>  $email]);
    }

    public function store(User $user): int
    {
        $this->entityManeger->persist($user);
        $this->entityManeger->flush();
        return $user->getId();
    }

    public function delete(User $user): void
    {
        $this->entityManeger->remove($user);
        $this->entityManeger->flush();
    }

    public function listAll(): array
    {
        return $this->repository->findAll();
    }

}
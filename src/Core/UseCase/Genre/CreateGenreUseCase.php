<?php

namespace Core\UseCase\Genre;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\CreateGenre\GenreCreateInputDTO;
use Core\DTO\Genre\CreateGenre\GenreCreateOutputDTO;
use Core\UseCase\Interfaces\TransactionInterface;

class CreateGenreUseCase
{
    protected $repository;

    protected $transaction;

    protected $categoryRepository;

    public function __construct(GenreRepositoryInterface $repo, TransactionInterface $trans, CategoryRepositoryInterface $categoryRepo)
    {
        $this->repository = $repo;
        $this->transaction = $trans;
        $this->categoryRepository = $categoryRepo;
    }

    public function execute(GenreCreateInputDTO $input): GenreCreateOutputDTO
    {

        try {

            $this->validateCategoriesId($input->categoriesId);

            $genre = new Genre(
                name: $input->name,
                isActive: $input->isActive,
                categoriesId: $input->categoriesId
            );

            $newGenre = $this->repository->insert($genre);

            $this->transaction->commit();

            return new GenreCreateOutputDTO(
                id: (string) $newGenre->id(),
                name: $newGenre->name,
                is_active: $newGenre->isActive,
                created_at: $newGenre->createdAt(),
            );
        } catch (\Throwable $th) {
            $this->transaction->rollBack();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoriesId = []): void
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff) > 0) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) === 1 ? 'Category' : 'Categories',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
        }

        // if (count($categoriesDb) !== count($categoriesId)) {
        //     throw new NotFoundException('Categories not found');
        // }

        //    foreach ($categoriesDb as $category) {
        //        if (! in_array($category, $categoriesId)) {
        //             throw new NotFoundException('Categories not found');
        //        }
        //    }

    }
}

<?php

namespace Core\UseCase\Genre;

use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\DTO\Genre\UpdateGenre\GenreUpdateInputDTO;
use Core\DTO\Genre\UpdateGenre\GenreUpdateOutputDTO;
use Core\UseCase\Interfaces\TransactionInterface;

class UpdateGenreUseCase
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

    public function execute(GenreUpdateInputDTO $input): GenreUpdateOutputDTO
    {

        $genre = $this->repository->findById($input->id);

        try {

            $this->validateCategoriesId($input->categoriesId);

            $genre->update(
                name: $input->name
            );

            foreach ($input->categoriesId as $categoryId) {
                $genre->addCategory($categoryId);
            }

            $genreUpdated = $this->repository->update($genre);

            $this->transaction->commit();

            return new GenreUpdateOutputDTO(
                id: (string) $genreUpdated->id(),
                name: $genreUpdated->name,
                is_active: $genreUpdated->isActive,
                created_at: $genreUpdated->createdAt(),
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

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\CategoryCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryCollectionResponseTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Category\Business\Category\IdentifierBuilder\CategoryIdentifierBuilderInterface;
use Spryker\Zed\Category\Business\Category\Validator\CategoryValidatorInterface;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryCreator implements CategoryCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Creator\CategoryRelationshipCreatorInterface
     */
    protected $categoryRelationshipCreator;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\Category\Business\Category\Validator\CategoryValidatorInterface
     */
    protected CategoryValidatorInterface $categoryValidator;

    /**
     * @var \Spryker\Zed\Category\Business\Category\IdentifierBuilder\CategoryIdentifierBuilderInterface
     */
    protected CategoryIdentifierBuilderInterface $categoryIdentifierBuilder;

    /**
     * @var array<\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface>
     */
    protected array $categoryPostCreatePlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Creator\CategoryRelationshipCreatorInterface $categoryRelationshipCreator
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Category\Business\Category\Validator\CategoryValidatorInterface $categoryValidator
     * @param \Spryker\Zed\Category\Business\Category\IdentifierBuilder\CategoryIdentifierBuilderInterface $categoryIdentifierBuilder
     * @param array<\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface> $categoryPostCreatePlugins
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryRepositoryInterface $categoryRepository,
        CategoryRelationshipCreatorInterface $categoryRelationshipCreator,
        CategoryToEventFacadeInterface $eventFacade,
        CategoryValidatorInterface $categoryValidator,
        CategoryIdentifierBuilderInterface $categoryIdentifierBuilder,
        array $categoryPostCreatePlugins
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryRepository = $categoryRepository;
        $this->categoryRelationshipCreator = $categoryRelationshipCreator;
        $this->eventFacade = $eventFacade;
        $this->categoryValidator = $categoryValidator;
        $this->categoryIdentifierBuilder = $categoryIdentifierBuilder;
        $this->categoryPostCreatePlugins = $categoryPostCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function createCategory(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer): void {
            $this->executeCreateCategoryTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCreateCategoryTransaction(CategoryTransfer $categoryTransfer): void
    {
        $this->eventFacade->trigger(CategoryEvents::CATEGORY_BEFORE_CREATE, $categoryTransfer);

        $this->categoryEntityManager->createCategory($categoryTransfer);
        $this->categoryRelationshipCreator->createCategoryRelationships($categoryTransfer);

        $this->triggerAfterCreateEvents($categoryTransfer);
        $this->executePostCreatePlugins($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function triggerAfterCreateEvents(CategoryTransfer $categoryTransfer): void
    {
        $this->eventFacade->trigger(CategoryEvents::CATEGORY_AFTER_CREATE, $categoryTransfer);

        $this->eventFacade->trigger(
            CategoryEvents::CATEGORY_AFTER_PUBLISH_CREATE,
            (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executePostCreatePlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryPostCreatePlugins as $categoryPostCreatePlugin) {
            $categoryPostCreatePlugin->execute($categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionRequestTransfer $categoryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    public function createCategoryCollection(
        CategoryCollectionRequestTransfer $categoryCollectionRequestTransfer
    ): CategoryCollectionResponseTransfer {
        $categoryCollectionResponseTransfer = new CategoryCollectionResponseTransfer();
        $categoryCollectionResponseTransfer->setCategories($categoryCollectionRequestTransfer->getCategories());

        $categoryCollectionResponseTransfer = $this->categoryValidator->validateCollection($categoryCollectionResponseTransfer);

        if ($categoryCollectionRequestTransfer->getIsTransactional() && $categoryCollectionResponseTransfer->getErrors()->count()) {
            return $categoryCollectionResponseTransfer;
        }

        $categoryCollectionResponseTransfer = $this->filterInvalidCategories($categoryCollectionResponseTransfer);

        // This will save all entities in one transaction. If any of the entities in the collection fails to be persisted
        // it will roll all of them back. And we don't catch exceptions here intentionally!
        return $this->getTransactionHandler()->handleTransaction(function () use ($categoryCollectionResponseTransfer) {
            return $this->executeCreateCategoryCollectionTransaction($categoryCollectionResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    protected function filterInvalidCategories(
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): CategoryCollectionResponseTransfer {
        $categoryIdsWithErrors = $this->getCategoryIdsWithErrors($categoryCollectionResponseTransfer->getErrors());

        $categoryTransfers = $categoryCollectionResponseTransfer->getCategories();
        $categoryCollectionResponseTransfer->setCategories(new ArrayObject());

        foreach ($categoryTransfers as $categoryTransfer) {
            // Check each SINGLE item before it is saved for errors, if it has some continue with the next one.
            if (!in_array($this->categoryIdentifierBuilder->buildIdentifier($categoryTransfer), $categoryIdsWithErrors, true)) {
                $categoryCollectionResponseTransfer->addCategory($categoryTransfer);
            }
        }

        return $categoryCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    protected function executeCreateCategoryCollectionTransaction(
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): CategoryCollectionResponseTransfer {
        $persistedCategoryTransfers = [];

        $categoryTemplateTransfer = $this->categoryRepository->getDefaultCategoryTemplate();

        foreach ($categoryCollectionResponseTransfer->getCategories() as $categoryTransfer) {
            if (!$categoryTransfer->getCategoryTemplate()) {
                $categoryTransfer->setCategoryTemplate($categoryTemplateTransfer);
                $categoryTransfer->setFkCategoryTemplate($categoryTemplateTransfer->getIdCategoryTemplate());
            }

            $this->createCategory($categoryTransfer);
            $persistedCategoryTransfers[] = $categoryTransfer;
        }

        $categoryCollectionResponseTransfer->setCategories(new ArrayObject($persistedCategoryTransfers));

        return $categoryCollectionResponseTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return array<string|null>
     */
    protected function getCategoryIdsWithErrors(ArrayObject $errorTransfers): array
    {
        return array_unique(array_map(static function (ErrorTransfer $errorTransfer): ?string {
            return $errorTransfer->getEntityIdentifier();
        }, $errorTransfers->getArrayCopy()));
    }
}

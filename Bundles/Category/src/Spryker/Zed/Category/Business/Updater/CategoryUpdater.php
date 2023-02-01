<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

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
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryUpdater implements CategoryUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected CategoryEntityManagerInterface $categoryEntityManager;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryRelationshipUpdaterInterface
     */
    protected CategoryRelationshipUpdaterInterface $categoryRelationshipUpdater;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected CategoryToEventFacadeInterface $eventFacade;

    /**
     * @var array<\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface>
     */
    protected array $categoryUpdateAfterPlugins;

    /**
     * @var \Spryker\Zed\Category\Business\Category\Validator\CategoryValidatorInterface
     */
    protected CategoryValidatorInterface $categoryValidator;

    /**
     * @var \Spryker\Zed\Category\Business\Category\IdentifierBuilder\CategoryIdentifierBuilderInterface
     */
    protected CategoryIdentifierBuilderInterface $categoryIdentifierBuilder;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\Updater\CategoryRelationshipUpdaterInterface $categoryRelationshipUpdater
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Category\Business\Category\Validator\CategoryValidatorInterface $categoryValidator
     * @param \Spryker\Zed\Category\Business\Category\IdentifierBuilder\CategoryIdentifierBuilderInterface $categoryIdentifierBuilder
     * @param array<\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUpdateAfterPluginInterface> $categoryUpdateAfterPlugins
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryRelationshipUpdaterInterface $categoryRelationshipUpdater,
        CategoryToEventFacadeInterface $eventFacade,
        CategoryValidatorInterface $categoryValidator,
        CategoryIdentifierBuilderInterface $categoryIdentifierBuilder,
        array $categoryUpdateAfterPlugins
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryRelationshipUpdater = $categoryRelationshipUpdater;
        $this->eventFacade = $eventFacade;
        $this->categoryValidator = $categoryValidator;
        $this->categoryIdentifierBuilder = $categoryIdentifierBuilder;
        $this->categoryUpdateAfterPlugins = $categoryUpdateAfterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategory(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeUpdateCategoryTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionRequestTransfer $categoryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    public function updateCategoryCollection(
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
            return $this->executeUpdateCategoryCollectionResponseTransaction($categoryCollectionResponseTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeUpdateCategoryTransaction(CategoryTransfer $categoryTransfer): void
    {
        $this->eventFacade->trigger(CategoryEvents::CATEGORY_BEFORE_UPDATE, $categoryTransfer);

        $this->categoryRelationshipUpdater->updateCategoryRelationships($categoryTransfer);
        $this->categoryEntityManager->updateCategory($categoryTransfer);

        $this->triggerAfterUpdateEvents($categoryTransfer);
        $this->executeCategoryUpdateAfterPlugins($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function triggerAfterUpdateEvents(CategoryTransfer $categoryTransfer): void
    {
        $this->eventFacade->trigger(CategoryEvents::CATEGORY_AFTER_UPDATE, $categoryTransfer);

        $this->eventFacade->trigger(
            CategoryEvents::CATEGORY_AFTER_PUBLISH_UPDATE,
            (new EventEntityTransfer())->setId($categoryTransfer->getIdCategory()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCategoryUpdateAfterPlugins(CategoryTransfer $categoryTransfer): void
    {
        foreach ($this->categoryUpdateAfterPlugins as $categoryUpdateAfterPlugin) {
            $categoryUpdateAfterPlugin->execute($categoryTransfer);
        }
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
    protected function executeUpdateCategoryCollectionResponseTransaction(
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): CategoryCollectionResponseTransfer {
        $persistedCategoryTransfers = [];

        foreach ($categoryCollectionResponseTransfer->getCategories() as $categoryTransfer) {
            $this->updateCategory($categoryTransfer);
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

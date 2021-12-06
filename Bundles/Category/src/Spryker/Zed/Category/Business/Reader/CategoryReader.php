<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Reader;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Category\Business\Model\Category\CategoryHydratorInterface;
use Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @uses \Spryker\Zed\Category\Persistence\CategoryRepository::KEY_ID_CATEGORY_NODE
     *
     * @var string
     */
    protected const KEY_ID_CATEGORY_NODE = 'id_category_node';

    /**
     * @uses \Spryker\Zed\Category\Persistence\CategoryRepository::KEY_CATEGORY_KEY
     *
     * @var string
     */
    protected const KEY_CATEGORY_KEY = 'category_key';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Model\Category\CategoryHydratorInterface
     */
    protected $categoryHydrator;

    /**
     * @var \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface
     */
    protected $categoryTreeReader;

    /**
     * @var array<\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryTransferExpanderPluginInterface>
     */
    protected $categoryTransferExpanderPlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Model\Category\CategoryHydratorInterface $categoryHydrator
     * @param \Spryker\Zed\Category\Business\Tree\CategoryTreeReaderInterface $categoryTreeReader
     * @param array<\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryTransferExpanderPluginInterface> $categoryTransferExpanderPlugins
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryHydratorInterface $categoryHydrator,
        CategoryTreeReaderInterface $categoryTreeReader,
        array $categoryTransferExpanderPlugins
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryHydrator = $categoryHydrator;
        $this->categoryTreeReader = $categoryTreeReader;
        $this->categoryTransferExpanderPlugins = $categoryTransferExpanderPlugins;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer
    {
        $categoryTransfer = $this->categoryRepository->findCategoryById($idCategory);
        if (!$categoryTransfer) {
            return null;
        }

        return $this->executeCategoryTransferExpanderPlugins($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCriteriaTransfer $categoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategory(CategoryCriteriaTransfer $categoryCriteriaTransfer): ?CategoryTransfer
    {
        $categoryTransfer = $this->categoryRepository->findCategoryByCriteria($categoryCriteriaTransfer);

        if (!$categoryTransfer) {
            return null;
        }

        if ($categoryCriteriaTransfer->getWithChildren() || $categoryCriteriaTransfer->getWithChildrenRecursively()) {
            $categoryNodeCollectionTransfer = $this->categoryTreeReader->getCategoryNodeCollectionTree(
                $categoryTransfer,
                $categoryCriteriaTransfer,
            );

            $categoryTransfer->setNodeCollection($categoryNodeCollectionTransfer);
        }

        return $this->executeCategoryTransferExpanderPlugins($categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        $categoryCollectionTransfer = $this->categoryRepository->getAllCategoryCollection($localeTransfer);
        $this->categoryHydrator->hydrateCategoryCollection($categoryCollectionTransfer, $localeTransfer);

        return $categoryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer
     *
     * @return array<int, array<string>>
     */
    public function getAscendantCategoryKeysGroupedByIdCategoryNode(CategoryNodeCriteriaTransfer $categoryNodeCriteriaTransfer): array
    {
        $ascendantCategoryKeys = $this->categoryRepository
            ->getAscendantCategoryKeys($categoryNodeCriteriaTransfer);

        return $this->groupAscendantCategoryKeysByIdCategoryNode($ascendantCategoryKeys);
    }

    /**
     * @param array<int, array<string>> $ascendantCategoryKeys
     *
     * @return array<int, array<string>>
     */
    protected function groupAscendantCategoryKeysByIdCategoryNode(array $ascendantCategoryKeys): array
    {
        $groupedCategoryKeys = [];

        foreach ($ascendantCategoryKeys as $ascendantCategoryKey) {
            $groupedCategoryKeys[(int)$ascendantCategoryKey[static::KEY_ID_CATEGORY_NODE]][] = $ascendantCategoryKey[static::KEY_CATEGORY_KEY];
        }

        return $groupedCategoryKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function executeCategoryTransferExpanderPlugins(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($this->categoryTransferExpanderPlugins as $categoryTransferExpanderPlugin) {
            $categoryTransfer = $categoryTransferExpanderPlugin->expandCategory($categoryTransfer);
        }

        return $categoryTransfer;
    }
}

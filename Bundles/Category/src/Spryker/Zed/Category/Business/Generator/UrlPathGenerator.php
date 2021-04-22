<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Generator;

use Generated\Shared\Transfer\CategoryNodeUrlPathCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class UrlPathGenerator implements UrlPathGeneratorInterface
{
    /**
     * @uses \Spryker\Zed\Category\Persistence\CategoryRepository::KEY_NAME
     */
    public const CATEGORY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\Category\Persistence\CategoryRepository::KEY_ID_CATEGORY_NODE
     */
    protected const KEY_ID_CATEGORY_NODE = 'id_category_node';

    /**
     * @uses \Spryker\Zed\Category\Persistence\CategoryRepository::KEY_FK_PARENT_CATEGORY_NODE
     */
    protected const KEY_FK_PARENT_CATEGORY_NODE = 'fk_parent_category_node';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUrlPathPluginInterface[]
     */
    protected $categoryUrlPathPlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUrlPathPluginInterface[] $categoryUrlPathPlugins
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, array $categoryUrlPathPlugins)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryUrlPathPlugins = $categoryUrlPathPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function buildCategoryNodeUrlForLocale(NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer): string
    {
        $pathParts = $this->getUrlPathPartsForCategoryNode($nodeTransfer, $localeTransfer);

        return $this->generate($pathParts);
    }

    /**
     * @param array $categoryPath
     *
     * @return string
     */
    public function generate(array $categoryPath): string
    {
        $formattedPath = [];

        foreach ($categoryPath as $category) {
            $categoryName = trim($category[static::CATEGORY_NAME]);

            if ($categoryName !== '') {
                $formattedPath[] = mb_strtolower(str_replace(' ', '-', $categoryName));
            }
        }

        return '/' . implode('/', $formattedPath);
    }

    /**
     * @param int[] $categoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function bulkBuildCategoryNodeUrlForLocale(array $categoryNodeIds, LocaleTransfer $localeTransfer): array
    {
        $categoryNodeUrlPathCriteriaTransfer = (new CategoryNodeUrlPathCriteriaTransfer())
            ->setCategoryNodeDescendantIds($categoryNodeIds)
            ->setIdLocale($localeTransfer->getIdLocaleOrFail())
            ->setExcludeRootNode(true);

        $categoryUrlPathParts = $this->categoryRepository->getBulkCategoryNodeUrlPathParts($categoryNodeUrlPathCriteriaTransfer);
        $indexedCategoryUrlPathParts = $this->getCategoryUrlPathPartIndexedByIdCategoryNode($categoryUrlPathParts);

        return $this->generateCategoryUrlPaths($indexedCategoryUrlPathParts);
    }

    /**
     * @param array $categoryUrlPathParts
     *
     * @return array
     */
    protected function getCategoryUrlPathPartIndexedByIdCategoryNode(array $categoryUrlPathParts): array
    {
        $indexedCategoryUrlPathParts = [];

        foreach ($categoryUrlPathParts as $categoryUrlPathPart) {
            $idCategoryNode = (int)$categoryUrlPathPart[static::KEY_ID_CATEGORY_NODE];
            $indexedCategoryUrlPathParts[$idCategoryNode][] = $categoryUrlPathPart;

            $indexedCategoryUrlPathParts = $this->addParentCategoryUrlPathParts(
                $idCategoryNode,
                $categoryUrlPathPart,
                $indexedCategoryUrlPathParts,
                $categoryUrlPathParts
            );
        }

        return $indexedCategoryUrlPathParts;
    }

    /**
     * @param int $idCategoryNode
     * @param array $categoryUrlPathPart
     * @param array $indexedCategoryUrlPathParts
     * @param array $categoryUrlPathParts
     *
     * @return array
     */
    protected function addParentCategoryUrlPathParts(
        int $idCategoryNode,
        array $categoryUrlPathPart,
        array $indexedCategoryUrlPathParts,
        array $categoryUrlPathParts
    ): array {
        $parentCategoryUrlPathPart = $categoryUrlPathParts[(int)$categoryUrlPathPart[static::KEY_FK_PARENT_CATEGORY_NODE]] ?? null;

        if (!$parentCategoryUrlPathPart) {
            return $indexedCategoryUrlPathParts;
        }

        array_unshift($indexedCategoryUrlPathParts[$idCategoryNode], $parentCategoryUrlPathPart);

        return $this->addParentCategoryUrlPathParts(
            $idCategoryNode,
            $parentCategoryUrlPathPart,
            $indexedCategoryUrlPathParts,
            $categoryUrlPathParts
        );
    }

    /**
     * @param array $indexedCategoryUrlPathParts
     *
     * @return string[]
     */
    protected function generateCategoryUrlPaths(array $indexedCategoryUrlPathParts): array
    {
        $indexedCategoryUrlPaths = [];

        foreach ($indexedCategoryUrlPathParts as $key => $categoryUrlPathParts) {
            $indexedCategoryUrlPaths[$key] = $this->generate($categoryUrlPathParts);
        }

        return $indexedCategoryUrlPaths;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getUrlPathPartsForCategoryNode(NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer): array
    {
        $categoryNodeUrlPathCriteriaTransfer = (new CategoryNodeUrlPathCriteriaTransfer())
            ->setIdCategoryNode($nodeTransfer->getIdCategoryNodeOrFail())
            ->setIdLocale($localeTransfer->getIdLocaleOrFail())
            ->setExcludeRootNode(true);

        $categoryUrlPathParts = $this->categoryRepository->getCategoryNodeUrlPathParts($categoryNodeUrlPathCriteriaTransfer);

        return $this->executeCategoryUrlPathPlugins($categoryUrlPathParts, $localeTransfer);
    }

    /**
     * @param array $categoryUrlPathParts
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function executeCategoryUrlPathPlugins(array $categoryUrlPathParts, LocaleTransfer $localeTransfer): array
    {
        foreach ($this->categoryUrlPathPlugins as $categoryUrlPathPlugin) {
            $categoryUrlPathParts = $categoryUrlPathPlugin->update($categoryUrlPathParts, $localeTransfer);
        }

        return $categoryUrlPathParts;
    }
}

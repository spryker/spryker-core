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
     *
     * @var string
     */
    public const CATEGORY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\Category\Persistence\CategoryRepository::KEY_FK_CATEGORY_NODE_DESCENDANT
     *
     * @var string
     */
    protected const KEY_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var array<\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUrlPathPluginInterface>
     */
    protected $categoryUrlPathPlugins;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param array<\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUrlPathPluginInterface> $categoryUrlPathPlugins
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
     * @param array<int> $categoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    public function bulkBuildCategoryNodeUrlForLocale(array $categoryNodeIds, LocaleTransfer $localeTransfer): array
    {
        $categoryNodeUrlPathCriteriaTransfer = (new CategoryNodeUrlPathCriteriaTransfer())
            ->setCategoryNodeDescendantIds($categoryNodeIds)
            ->setIdLocale($localeTransfer->getIdLocaleOrFail())
            ->setExcludeRootNode(true);

        $categoryUrlPathParts = $this->categoryRepository->getBulkCategoryNodeUrlPathParts($categoryNodeUrlPathCriteriaTransfer);
        $indexedCategoryUrlPathParts = $this->getCategoryUrlPathPartIndexedByIdCategoryNode($categoryUrlPathParts);

        return $this->generateCategoryUrlPaths($indexedCategoryUrlPathParts, $localeTransfer);
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
            $idCategoryNode = (int)$categoryUrlPathPart[static::KEY_FK_CATEGORY_NODE_DESCENDANT];
            $indexedCategoryUrlPathParts[$idCategoryNode][] = $categoryUrlPathPart;
        }

        return $indexedCategoryUrlPathParts;
    }

    /**
     * @param array $indexedCategoryUrlPathParts
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    protected function generateCategoryUrlPaths(array $indexedCategoryUrlPathParts, LocaleTransfer $localeTransfer): array
    {
        $indexedCategoryUrlPaths = [];

        foreach ($indexedCategoryUrlPathParts as $key => $categoryUrlPathParts) {
            $expandedCategoryUrlPathParts = $this->executeCategoryUrlPathPlugins($categoryUrlPathParts, $localeTransfer);
            $indexedCategoryUrlPaths[$key] = $this->generate($expandedCategoryUrlPathParts);
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

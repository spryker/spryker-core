<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Generator;

use Generated\Shared\Transfer\CategoryUrlPathCriteriaTransfer;
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
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getUrlPathPartsForCategoryNode(NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer): array
    {
        $categoryUrlPathCriteriaTransfer = (new CategoryUrlPathCriteriaTransfer())
            ->setIdCategoryNode($nodeTransfer->getIdCategoryNodeOrFail())
            ->setIdLocale($localeTransfer->getIdLocaleOrFail());

        $categoryUrlPathParts = $this->categoryRepository->getCategoryUrlPathParts($categoryUrlPathCriteriaTransfer);

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

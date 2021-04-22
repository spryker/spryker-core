<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeUrlCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryUrlUpdater implements CategoryUrlUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\CategoryConfig;
     */
    protected $categoryConfig;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface
     */
    protected $urlPathGenerator;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface $urlPathGenerator
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface $urlFacade
     * @param \Spryker\Zed\Category\CategoryConfig $categoryConfig
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        UrlPathGeneratorInterface $urlPathGenerator,
        CategoryToUrlInterface $urlFacade,
        CategoryConfig $categoryConfig
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->urlPathGenerator = $urlPathGenerator;
        $this->urlFacade = $urlFacade;
        $this->categoryConfig = $categoryConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function updateCategoryUrl(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeUpdateCategoryUrlTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeUpdateCategoryUrlTransaction(CategoryTransfer $categoryTransfer): void
    {
        $categoryNodeUrlCriteriaTransfer = new CategoryNodeUrlCriteriaTransfer();

        $categoryNodeChildCount = $this->categoryRepository->getCategoryNodeChildCountByParentNodeId($categoryTransfer);
        $categoryReadChunkSize = $this->categoryConfig->getCategoryReadChunkSize();

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setLimit($categoryReadChunkSize);

        for ($offset = 0; $offset <= $categoryNodeChildCount; $offset += $categoryReadChunkSize) {
            $categoryCriteriaTransfer->setOffset($offset);
            $categoryNodeChildIds = $this->categoryRepository->getCategoryNodeChildIdsByParentNodeId($categoryTransfer, $categoryCriteriaTransfer);
            $categoryNodeChildIds[] = $categoryTransfer->getIdCategoryOrFail();

            $categoryNodeUrlCriteriaTransfer->setCategoryNodeIds($categoryNodeChildIds);
            $urlTransfers = $this->categoryRepository->getCategoryNodeUrls($categoryNodeUrlCriteriaTransfer);

            $this->bulkUpdateCategoryNodeUrlsForLocale(
                $categoryNodeChildIds,
                $urlTransfers,
                $categoryTransfer
            );

            // old version
//            foreach ($categoryNodeChildIds as $categoryNodeChildId) {
//                $nodeTransfer = (new NodeTransfer())->setIdCategoryNode($categoryNodeChildId);
//
//                foreach ($categoryTransfer->getLocalizedAttributes() as $categoryLocalizedAttributesTransfer) {
//                    $this->updateCategoryNodeUrlsForLocale(
//                        $nodeTransfer,
//                        $urlTransfers,
//                        $categoryLocalizedAttributesTransfer->getLocaleOrFail()
//                    );
//                }
//            }
        }
    }

    /**
     * @param int[] $categoryNodeChildIds
     * @param \Generated\Shared\Transfer\UrlTransfer[] $urlTransfers
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function bulkUpdateCategoryNodeUrlsForLocale(
        array $categoryNodeChildIds,
        array $urlTransfers,
        CategoryTransfer $categoryTransfer
    ): void {
        foreach ($categoryTransfer->getLocalizedAttributes() as $categoryLocalizedAttributesTransfer) {
            $localeTransfer = $categoryLocalizedAttributesTransfer->getLocaleOrFail();
            $categoryName = $this->categoryRepository->findCategoryName($categoryTransfer, $localeTransfer);

            if ($categoryLocalizedAttributesTransfer->getName() === $categoryName) {
                continue;
            }

            $urlPaths = $this->urlPathGenerator->bulkBuildCategoryNodeUrlForLocale(
                $categoryNodeChildIds,
                $categoryLocalizedAttributesTransfer->getLocaleOrFail()
            );

            // TODO: move to separate method.
            foreach ($urlTransfers as $urlTransfer) {
                if ($urlTransfer->getFkLocaleOrFail() != $localeTransfer->getIdLocaleOrFail()) {
                    continue;
                }

                $urlPath = $urlPaths[$urlTransfer->getFkResourceCategorynodeOrFail()] ?? null;

                if (!$urlPath || $urlPath === $urlTransfer->getUrl()) {
                    continue;
                }

                $urlTransfer->setUrl($urlPath);
                $this->urlFacade->updateUrl($urlTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer[] $urlTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function updateCategoryNodeUrlsForLocale(NodeTransfer $nodeTransfer, array $urlTransfers, LocaleTransfer $localeTransfer): void
    {
        foreach ($urlTransfers as $urlTransfer) {
            if (
                $urlTransfer->getFkLocaleOrFail() != $localeTransfer->getIdLocaleOrFail()
                || $urlTransfer->getFkResourceCategorynodeOrFail() != $nodeTransfer->getIdCategoryNodeOrFail()
            ) {
                continue;
            }

            $urlPath = $this->urlPathGenerator->buildCategoryNodeUrlForLocale($nodeTransfer, $localeTransfer);
            if ($urlPath === $urlTransfer->getUrl()) {
                continue;
            }

            $urlTransfer->setUrl($urlPath);
            $this->urlFacade->updateUrl($urlTransfer);
        }
    }
}

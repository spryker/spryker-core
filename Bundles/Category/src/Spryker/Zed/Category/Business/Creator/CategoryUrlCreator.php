<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\CategoryUrlPathCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException;
use Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface;
use Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

class CategoryUrlCreator implements CategoryUrlCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface
     */
    protected $urlPathGenerator;

    /**
     * @var \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUrlPathPluginInterface[]
     */
    protected $categoryUrlPathPlugins;

    /**
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToUrlInterface $urlFacade
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Generator\UrlPathGeneratorInterface $urlPathGenerator
     * @param \Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryUrlPathPluginInterface[] $categoryUrlPathPlugins
     */
    public function __construct(
        CategoryToUrlInterface $urlFacade,
        CategoryRepositoryInterface $categoryRepository,
        UrlPathGeneratorInterface $urlPathGenerator,
        array $categoryUrlPathPlugins
    ) {
        $this->urlFacade = $urlFacade;
        $this->categoryRepository = $categoryRepository;
        $this->urlPathGenerator = $urlPathGenerator;
        $this->categoryUrlPathPlugins = $categoryUrlPathPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function createCategoryUrl(CategoryTransfer $categoryTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            $this->executeCreateCategoryUrlTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[]|\ArrayObject $categoryLocalizedAttributesTransfers
     *
     * @return void
     */
    public function createLocalizedCategoryUrlsForNode(NodeTransfer $nodeTransfer, ArrayObject $categoryLocalizedAttributesTransfers): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($nodeTransfer, $categoryLocalizedAttributesTransfers) {
            $this->executeCreateLocalizedCategoryUrlsForNodeTransaction($nodeTransfer, $categoryLocalizedAttributesTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function executeCreateCategoryUrlTransaction(CategoryTransfer $categoryTransfer): void
    {
        $nodeTransfer = $categoryTransfer->getCategoryNodeOrFail();

        foreach ($categoryTransfer->getLocalizedAttributes() as $categoryLocalizedAttributesTransfer) {
            $localeTransfer = $categoryLocalizedAttributesTransfer->getLocaleOrFail();
            $this->saveLocalizedUrlForNode($nodeTransfer, $localeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[]|\ArrayObject $categoryLocalizedAttributesTransfers
     *
     * @return void
     */
    public function executeCreateLocalizedCategoryUrlsForNodeTransaction(NodeTransfer $nodeTransfer, ArrayObject $categoryLocalizedAttributesTransfers): void
    {
        foreach ($categoryLocalizedAttributesTransfers as $categoryLocalizedAttributesTransfer) {
            $this->saveLocalizedUrlForNode($nodeTransfer, $categoryLocalizedAttributesTransfer->getLocaleOrFail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @throws \Spryker\Zed\Category\Business\Exception\CategoryUrlExistsException
     *
     * @return void
     */
    public function saveLocalizedUrlForNode(NodeTransfer $nodeTransfer, LocaleTransfer $localeTransfer): void
    {
        $urlTransfer = $this->createUrlTransfer($localeTransfer, $nodeTransfer);
        if ($this->urlFacade->hasUrlCaseInsensitive($urlTransfer)) {
            return;
        }

        try {
            $this->urlFacade->createUrl($urlTransfer);
        } catch (UrlExistsException $e) {
            throw new CategoryUrlExistsException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(LocaleTransfer $localeTransfer, NodeTransfer $categoryNodeTransfer): UrlTransfer
    {
        return (new UrlTransfer())
            ->setFkLocale($localeTransfer->getIdLocaleOrFail())
            ->setFkResourceCategorynode($categoryNodeTransfer->getIdCategoryNodeOrFail())
            ->setUrl($this->buildUrl($categoryNodeTransfer, $localeTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function buildUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer): string
    {
        $pathParts = $this->getUrlPathPartsForCategoryNode($categoryNodeTransfer, $localeTransfer);

        return $this->urlPathGenerator->generate($pathParts);
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

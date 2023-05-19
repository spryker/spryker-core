<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig;

class ProductAbstractFormDataProvider implements ProductAbstractFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface
     */
    protected ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface
     */
    protected ProductMerchantPortalGuiToCategoryFacadeInterface $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface
     */
    protected ProductMerchantPortalGuiToProductCategoryFacadeInterface $productCategoryFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig
     */
    protected ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface $productCategoryFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade,
        ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductMerchantPortalGuiToCategoryFacadeInterface $categoryFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToProductCategoryFacadeInterface $productCategoryFacade,
        ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig
    ) {
        $this->merchantProductFacade = $merchantProductFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
        $this->productCategoryFacade = $productCategoryFacade;
        $this->productMerchantPortalGuiConfig = $productMerchantPortalGuiConfig;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer|null
     */
    public function findMerchantProduct(int $idProductAbstract, int $idMerchant): ?MerchantProductTransfer
    {
        $merchantProductTransfer = $this->merchantProductFacade->findMerchantProduct(
            (new MerchantProductCriteriaTransfer())
                ->addIdMerchant($idMerchant)
                ->setIdProductAbstract($idProductAbstract),
        );

        if (!$merchantProductTransfer || !$merchantProductTransfer->getProductAbstract()) {
            return null;
        }

        $productAbstractTransfer = $this->expandProductAbstractWithCategoryIds(
            $merchantProductTransfer->getProductAbstractOrFail(),
        );

        return $merchantProductTransfer->setProductAbstract($productAbstractTransfer);
    }

    /**
     * @return array<array<int>>
     */
    public function getOptions(): array
    {
        return [
            ProductAbstractForm::OPTION_STORE_CHOICES => $this->getStoreChoices(),
            ProductAbstractForm::OPTION_PRODUCT_CATEGORY_CHOICES => $this->getProductCategoryChoices(),
        ];
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getProductCategoryTree(): array
    {
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($this->productMerchantPortalGuiConfig->getMainCategoryIdForCategoryFilter())
            ->setLocaleName($this->localeFacade->getCurrentLocale()->getLocaleName())
            ->setWithChildrenRecursively(true);
        $categoryTransfer = $this->categoryFacade->findCategory($categoryCriteriaTransfer);

        if (!$categoryTransfer || !$categoryTransfer->getNodeCollection()) {
            return [];
        }

        $nodeCollectionTransfer = $this->getCategoryChildNodeCollection($categoryTransfer);

        return $this->getCategoryTreeArray($nodeCollectionTransfer);
    }

    /**
     * @return array<string, int>
     */
    protected function getStoreChoices(): array
    {
        $storeChoices = [];

        $storeTransfers = $this->getCurrentMerchantStores();

        foreach ($storeTransfers as $storeTransfer) {
            $idStore = $storeTransfer->getIdStoreOrFail();
            $storeName = $storeTransfer->getNameOrFail();
            $storeChoices[$storeName] = $idStore;
        }

        return $storeChoices;
    }

    /**
     * @return list<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getCurrentMerchantStores(): array
    {
        $merchantTransfer = $this->merchantUserFacade
            ->getCurrentMerchantUser()
            ->getMerchant();

        if (!$merchantTransfer || !$merchantTransfer->getStoreRelation()) {
            return [];
        }

        return $merchantTransfer->getStoreRelationOrFail()
            ->getStores()
            ->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $nodeCollectionTransfer
     *
     * @return array<array<string, mixed>>
     */
    protected function getCategoryTreeArray(NodeCollectionTransfer $nodeCollectionTransfer): array
    {
        $categoryTreeArray = [];
        foreach ($nodeCollectionTransfer->getNodes() as $nodeTransfer) {
            $categoryTreeArray[] = [
                'value' => (string)$nodeTransfer->getIdCategoryNode(),
                'title' => $nodeTransfer->getCategoryOrFail()->getLocalizedAttributes()->offsetGet(0)->getName(),
                'children' => $this->getCategoryTreeArray($nodeTransfer->getChildrenNodesOrFail()),
            ];
        }

        return $categoryTreeArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function expandProductAbstractWithCategoryIds(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        $categoryCollectionTransfer = $this->productCategoryFacade->getCategoryTransferCollectionByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
            $this->localeFacade->getCurrentLocale(),
        );
        $productAbstractTransfer->setCategoryIds($this->getCategoryIds($categoryCollectionTransfer));

        return $productAbstractTransfer;
    }

    /**
     * @return array<int>
     */
    protected function getProductCategoryChoices(): array
    {
        $categoryCollectionTransfer = $this->categoryFacade
            ->getAllCategoryCollection($this->localeFacade->getCurrentLocale());

        return $this->getCategoryIds($categoryCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return array<int>
     */
    protected function getCategoryIds(CategoryCollectionTransfer $categoryCollectionTransfer): array
    {
        $categoryIds = [];
        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $categoryIds[] = $categoryTransfer->getIdCategoryOrFail();
        }

        return $categoryIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    protected function getCategoryChildNodeCollection(CategoryTransfer $categoryTransfer): NodeCollectionTransfer
    {
        $categoryNodeCollectionTransfer = $categoryTransfer->getNodeCollection();
        if (!$categoryNodeCollectionTransfer || $categoryNodeCollectionTransfer->getNodes()->count() === 0) {
            return new NodeCollectionTransfer();
        }

        return $categoryNodeCollectionTransfer->getNodes()->offsetGet(0)->getChildrenNodes() ?? new NodeCollectionTransfer();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig;

class ProductAbstractFormDataProvider implements ProductAbstractFormDataProviderInterface
{
    /**
     * @uses \Spryker\Zed\Category\Business\Tree\CategoryTreeReader::ID_CATEGORY
     */
    protected const KEY_NODE_CHILD_ID_CATEGORY = 'id_category';

    /**
     * @uses \Spryker\Zed\Category\Business\Tree\CategoryTreeReader::TEXT
     */
    protected const KEY_NODE_CHILD_TEXT = 'text';

    /**
     * @uses \Spryker\Zed\Category\Business\Tree\Formatter\CategoryTreeFormatter::CHILDREN
     */
    protected const KEY_NODE_CHILD_CHILDREN = 'children';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface
     */
    protected $productCategoryFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig
     */
    protected $productMerchantPortalGuiConfig;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCategoryFacadeInterface $categoryFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductCategoryFacadeInterface $productCategoryFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig
     */
    public function __construct(
        ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade,
        ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        ProductMerchantPortalGuiToCategoryFacadeInterface $categoryFacade,
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToProductCategoryFacadeInterface $productCategoryFacade,
        ProductMerchantPortalGuiConfig $productMerchantPortalGuiConfig
    ) {
        $this->merchantProductFacade = $merchantProductFacade;
        $this->storeFacade = $storeFacade;
        $this->categoryFacade = $categoryFacade;
        $this->localeFacade = $localeFacade;
        $this->productCategoryFacade = $productCategoryFacade;
        $this->productMerchantPortalGuiConfig = $productMerchantPortalGuiConfig;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstract(int $idProductAbstract, int $idMerchant): ?ProductAbstractTransfer
    {
        $productAbstractTransfer = $this->merchantProductFacade->findProductAbstract(
            (new MerchantProductCriteriaTransfer())->addIdMerchant($idMerchant)->setIdProductAbstract($idProductAbstract)
        );
        $productAbstractTransfer = $this->expandProductAbstractWithCategoryIds($productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @return int[][]
     */
    public function getOptions(): array
    {
        return [
            ProductAbstractForm::OPTION_STORE_CHOICES => $this->getStoreChoices(),
            ProductAbstractForm::OPTION_PRODUCT_CATEGORY_CHOICES => $this->getProductCategoryChoices(),
        ];
    }

    /**
     * @return mixed[]
     */
    public function getProductCategoryTree(): array
    {
        $treeNodeChildren = $this->categoryFacade->getTreeNodeChildrenByIdCategoryAndLocale(
            $this->productMerchantPortalGuiConfig->getMainCategoryIdForCategoryOptions(),
            $this->localeFacade->getCurrentLocale()
        );

        return $this->getCategoryTreeArray($treeNodeChildren);
    }

    /**
     * @return int[]
     */
    protected function getStoreChoices(): array
    {
        $storeChoices = [];

        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            /** @var int $idStore */
            $idStore = $storeTransfer->requireIdStore()->getIdStore();
            /** @var string $storeName */
            $storeName = $storeTransfer->requireName()->getName();
            $storeChoices[$storeName] = $idStore;
        }

        return $storeChoices;
    }

    /**
     * @param mixed[] $treeNodeChildren
     *
     * @return mixed[]
     */
    protected function getCategoryTreeArray(array $treeNodeChildren): array
    {
        $categoryTreeArray = [];
        foreach ($treeNodeChildren as $child) {
            $categoryTreeArray[] = [
                'value' => $child[static::KEY_NODE_CHILD_ID_CATEGORY],
                'title' => $child[static::KEY_NODE_CHILD_TEXT],
                'children' => $this->getCategoryTreeArray($child[static::KEY_NODE_CHILD_CHILDREN]),
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
            $productAbstractTransfer->getIdProductAbstract(),
            $this->localeFacade->getCurrentLocale()
        );
        $productAbstractTransfer->setCategoryIds($this->getCategoryIds($categoryCollectionTransfer));

        return $productAbstractTransfer;
    }

    /**
     * @return int[]
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
     * @return int[]
     */
    protected function getCategoryIds(CategoryCollectionTransfer $categoryCollectionTransfer): array
    {
        $categoryIds = [];
        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $categoryIds[] = $categoryTransfer->getIdCategory();
        }

        return $categoryIds;
    }
}

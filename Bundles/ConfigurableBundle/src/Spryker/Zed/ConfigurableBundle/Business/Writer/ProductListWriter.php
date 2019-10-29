<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface;

class ProductListWriter implements ProductListWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap::COL_TYPE_WHITELIST
     */
    protected const PRODUCT_LIST_DEFAULT_TYPE = 'whitelist';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface $productListFacade
     */
    public function __construct(ConfigurableBundleToProductListFacadeInterface $productListFacade)
    {
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function createProductList(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): ProductListResponseTransfer
    {
        $productListTransfer = (new ProductListTransfer())
            ->setTitle($this->generateProductListTitle($configurableBundleTemplateSlotTransfer))
            ->setType(static::PRODUCT_LIST_DEFAULT_TYPE)
            ->setProductListProductConcreteRelation(new ProductListProductConcreteRelationTransfer())
            ->setProductListCategoryRelation(new ProductListCategoryRelationTransfer());

        return $this->productListFacade->createProductList($productListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function updateProductList(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): ProductListResponseTransfer
    {
        $configurableBundleTemplateSlotTransfer
            ->requireProductList()
            ->getProductList()
                ->requireIdProductList();

        $productListTransfer = $configurableBundleTemplateSlotTransfer->getProductList();
        $productListTransfer->setTitle($this->generateProductListTitle($configurableBundleTemplateSlotTransfer));

        return $this->productListFacade->updateProductList($productListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return string
     */
    protected function generateProductListTitle(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): string
    {
        $configurableBundleTemplateSlotTransfer
            ->requireTranslations()
            ->requireConfigurableBundleTemplate()
            ->getConfigurableBundleTemplate()
                ->requireTranslations();

        return sprintf(
            '%s - %s',
            $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()->getTranslations()[0]->getName(),
            $configurableBundleTemplateSlotTransfer->getTranslations()[0]->getName()
        );
    }
}

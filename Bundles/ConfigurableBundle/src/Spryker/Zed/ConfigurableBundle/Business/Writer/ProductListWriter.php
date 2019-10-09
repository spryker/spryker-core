<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductListCategoryRelationTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Spryker\Zed\ConfigurableBundle\Business\Exception\ConfigurableBundleTemplateNotFoundException;
use Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface;

class ProductListWriter implements ProductListWriterInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface
     */
    protected $configurableBundleTemplateReader;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface
     */
    protected $productListFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Business\Reader\ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToProductListFacadeInterface $productListFacade
     */
    public function __construct(
        ConfigurableBundleTemplateReaderInterface $configurableBundleTemplateReader,
        ConfigurableBundleToProductListFacadeInterface $productListFacade
    ) {
        $this->configurableBundleTemplateReader = $configurableBundleTemplateReader;
        $this->productListFacade = $productListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function createProductListForConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ProductListResponseTransfer {
        $configurableBundleTemplateSlotTransfer->requireFkConfigurableBundleTemplate();

        $configurableBundleTemplateTransfer = $this->getConfigurableBundleTemplateTransfer($configurableBundleTemplateSlotTransfer);
        $configurableBundleTemplateSlotTransfer->setConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        return $this->createProductList($configurableBundleTemplateSlotTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function updateProductListForConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ProductListResponseTransfer {
        $configurableBundleTemplateSlotTransfer->requireFkConfigurableBundleTemplate()
            ->requireTranslations()
            ->requireProductList()
            ->getProductList()
            ->requireIdProductList();

        $configurableBundleTemplateTransfer = $this->getConfigurableBundleTemplateTransfer($configurableBundleTemplateSlotTransfer);
        $configurableBundleTemplateSlotTransfer->setConfigurableBundleTemplate($configurableBundleTemplateTransfer);

        $configurableBundleTemplateSlotTransfer->getProductList()->setTitle(
            $this->generateProductListTitle($configurableBundleTemplateSlotTransfer)
        );

        return $this->productListFacade->updateProductList($configurableBundleTemplateSlotTransfer->getProductList());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @throws \Spryker\Zed\ConfigurableBundle\Business\Exception\ConfigurableBundleTemplateNotFoundException
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    protected function getConfigurableBundleTemplateTransfer(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): ConfigurableBundleTemplateTransfer
    {
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateSlotTransfer->getFkConfigurableBundleTemplate());

        $configurableBundleTemplateTransfer = $this->configurableBundleTemplateReader
            ->findConfigurableBundleTemplateWithDefaultLocaleTranslation($configurableBundleTemplateFilterTransfer);

        if (!$configurableBundleTemplateTransfer) {
            throw new ConfigurableBundleTemplateNotFoundException(
                sprintf('Configurable Bundle Template with id %s does not exist.', $configurableBundleTemplateSlotTransfer->getFkConfigurableBundleTemplate())
            );
        }

        return $configurableBundleTemplateTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function createProductList(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): ProductListResponseTransfer
    {
        $productListTransfer = $this->createProductListTransfer($configurableBundleTemplateSlotTransfer);

        return $this->productListFacade->createProductList($productListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function createProductListTransfer(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): ProductListTransfer
    {
        return (new ProductListTransfer())
            ->setTitle($this->generateProductListTitle($configurableBundleTemplateSlotTransfer))
            ->setType(SpyProductListTableMap::COL_TYPE_WHITELIST)
            ->setProductListProductConcreteRelation(new ProductListProductConcreteRelationTransfer())
            ->setProductListCategoryRelation(new ProductListCategoryRelationTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return string
     */
    protected function generateProductListTitle(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): string
    {
        $configurableBundleTemplateSlotTransfer->requireTranslations()
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

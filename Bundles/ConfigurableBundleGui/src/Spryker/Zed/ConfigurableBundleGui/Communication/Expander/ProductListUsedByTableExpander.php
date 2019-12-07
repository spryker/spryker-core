<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableTransfer;
use Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableMapperInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;

class ProductListUsedByTableExpander implements ProductListUsedByTableExpanderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface
     */
    protected $configurableBundleFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableMapperInterface
     */
    protected $productListUsedByTableMapper;

    /**
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ConfigurableBundleGui\Communication\Mapper\ProductListUsedByTableMapperInterface $productListUsedByTableMapper
     */
    public function __construct(
        ConfigurableBundleGuiToConfigurableBundleFacadeInterface $configurableBundleFacade,
        ConfigurableBundleGuiToLocaleFacadeInterface $localeFacade,
        ProductListUsedByTableMapperInterface $productListUsedByTableMapper
    ) {
        $this->configurableBundleFacade = $configurableBundleFacade;
        $this->productListUsedByTableMapper = $productListUsedByTableMapper;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableTransfer $productListUsedByTableTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    public function expandTableData(ProductListUsedByTableTransfer $productListUsedByTableTransfer): ProductListUsedByTableTransfer
    {
        $productListUsedByTableTransfer
            ->getProductList()
                ->requireIdProductList();

        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setProductList($productListUsedByTableTransfer->getProductList())
            ->setTranslationLocales(new ArrayObject([$this->localeFacade->getCurrentLocale()]));

        $configurableBundleTemplateSlotTransfers = $this->configurableBundleFacade
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer)
            ->getConfigurableBundleTemplateSlots();

        $productListUsedByTableTransfer = $this->expandProductListUsedByTableTransfer(
            $productListUsedByTableTransfer,
            $configurableBundleTemplateSlotTransfers
        );

        return $productListUsedByTableTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableTransfer $productListUsedByTableTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableTransfer
     */
    protected function expandProductListUsedByTableTransfer(
        ProductListUsedByTableTransfer $productListUsedByTableTransfer,
        ArrayObject $configurableBundleTemplateSlotTransfers
    ): ProductListUsedByTableTransfer {
        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $productListUsedByTableTransfer->addRow(
                $this->productListUsedByTableMapper->mapConfigurableBundleTemplateSlotTransferToProductListUsedByTableRowTransfer(
                    $configurableBundleTemplateSlotTransfer,
                    new ProductListUsedByTableRowTransfer()
                )
            );
        }

        return $productListUsedByTableTransfer;
    }
}

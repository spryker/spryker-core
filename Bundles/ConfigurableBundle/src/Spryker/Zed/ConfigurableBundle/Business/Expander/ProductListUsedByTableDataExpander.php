<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Expander;

use Generated\Shared\Transfer\ProductListUsedByTableDataTransfer;
use Generated\Shared\Transfer\ProductListUsedByTableRowTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Hydrator\ConfigurableBundleTranslationHydratorInterface;
use Spryker\Zed\ConfigurableBundle\Business\Mapper\ProductListUsedByTableDataMapperInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ProductListUsedByTableDataExpander implements ProductListUsedByTableDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Hydrator\ConfigurableBundleTranslationHydratorInterface
     */
    protected $configurableBundleTranslationHydrator;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Mapper\ProductListUsedByTableDataMapperInterface
     */
    protected $productListUsedByTableDataMapper;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Business\Hydrator\ConfigurableBundleTranslationHydratorInterface $configurableBundleTranslationHydrator
     * @param \Spryker\Zed\ConfigurableBundle\Business\Mapper\ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleTranslationHydratorInterface $configurableBundleTranslationHydrator,
        ProductListUsedByTableDataMapperInterface $productListUsedByTableDataMapper
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->productListUsedByTableDataMapper = $productListUsedByTableDataMapper;
        $this->configurableBundleTranslationHydrator = $configurableBundleTranslationHydrator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    public function expandTableData(ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer): ProductListUsedByTableDataTransfer
    {
        $configurableBundleTemplateSlotTransfers = $this->configurableBundleRepository->findConfigurableBundleTemplateSlotsByIdProductList(
            $productListUsedByTableDataTransfer->getProductList()->getIdProductList()
        );

        $configurableBundleTemplateSlotTransfers = $this->hydrateConfigurableBundleTemplateSlotTransfersWithTranslations(
            $configurableBundleTemplateSlotTransfers
        );

        $productListUsedByTableDataTransfer = $this->expandProductListUsedByTableDataTransfer(
            $productListUsedByTableDataTransfer,
            $configurableBundleTemplateSlotTransfers
        );

        return $productListUsedByTableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    protected function hydrateConfigurableBundleTemplateSlotTransfersWithTranslations(array $configurableBundleTemplateSlotTransfers): array
    {
        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $configurableBundleTemplateSlotTransfer = $this->configurableBundleTranslationHydrator
                ->hydrateConfigurableBundleTemplateSlotWithTranslationForCurrentLocale($configurableBundleTemplateSlotTransfer);

            $configurableBundleTemplateSlotTransfer->setConfigurableBundleTemplate(
                $this->configurableBundleTranslationHydrator->hydrateConfigurableBundleTemplateWithTranslationForCurrentLocale(
                    $configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()
                )
            );
        }

        return $configurableBundleTemplateSlotTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListUsedByTableDataTransfer
     */
    protected function expandProductListUsedByTableDataTransfer(
        ProductListUsedByTableDataTransfer $productListUsedByTableDataTransfer,
        array $configurableBundleTemplateSlotTransfers
    ): ProductListUsedByTableDataTransfer {
        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $productListUsedByTableDataTransfer->addRow(
                $this->productListUsedByTableDataMapper->mapConfigurableBundleTemplateSlotTransferToProductListUsedByTableRowTransfer(
                    $configurableBundleTemplateSlotTransfer,
                    new ProductListUsedByTableRowTransfer()
                )
            );
        }

        return $productListUsedByTableDataTransfer;
    }
}

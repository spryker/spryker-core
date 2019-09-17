<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateSlotReader implements ConfigurableBundleTemplateSlotReaderInterface
{
    protected const ERROR_MESSAGE_UNBABLE_TO_DELETE_PRODUCT_LIST = 'Unable to delete Product List since it used by Configurable Bundle Template "%template%" ("%slot%" slot).';

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Dependency\Facade\ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongSlots(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        $configurableBundleTemplateSlotTransfers = $this->configurableBundleRepository->findProductListUsageAmongSlots($productListTransfer->getIdProductList());

        return $this->createProductListResponseTransfer($productListTransfer, $configurableBundleTemplateSlotTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function createProductListResponseTransfer(ProductListTransfer $productListTransfer, array $configurableBundleTemplateSlotTransfers): ProductListResponseTransfer
    {
        $productListResponseTransfer = (new ProductListResponseTransfer())->setProductList()->setIsSuccessful(true);

        if (!$configurableBundleTemplateSlotTransfers) {
            return $productListResponseTransfer;
        }

        $productListResponseTransfer = $this->expandProductListResponseTransferWithMessages($productListResponseTransfer, $configurableBundleTemplateSlotTransfers);

        return $productListResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[] $configurableBundleTemplateSlotTransfers
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListResponseTransferWithMessages(
        ProductListResponseTransfer $productListResponseTransfer,
        array $configurableBundleTemplateSlotTransfers
    ): ProductListResponseTransfer {
        foreach ($configurableBundleTemplateSlotTransfers as $configurableBundleTemplateSlotTransfer) {
            $template = $this->glossaryFacade->translate($configurableBundleTemplateSlotTransfer->getConfigurableBundleTemplate()->getName());
            $slot = $this->glossaryFacade->translate($configurableBundleTemplateSlotTransfer->getName());

            $productListResponseTransfer->addMessage(
                (new MessageTransfer())->setValue(static::ERROR_MESSAGE_UNBABLE_TO_DELETE_PRODUCT_LIST)
                    ->setParameters([
                        '%template%' => $template,
                        '%slot%' => $slot,
                    ])
            );
        }

        return $productListResponseTransfer;
    }
}

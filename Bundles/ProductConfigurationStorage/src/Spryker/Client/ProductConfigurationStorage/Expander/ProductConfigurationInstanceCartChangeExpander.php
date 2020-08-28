<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface;

class ProductConfigurationInstanceCartChangeExpander implements ProductConfigurationInstanceCartChangeExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface
     */
    protected $productConfigurationInstanceReader;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface $productConfigurationInstanceReader
     */
    public function __construct(ProductConfigurationInstanceReaderInterface $productConfigurationInstanceReader)
    {
        $this->productConfigurationInstanceReader = $productConfigurationInstanceReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithProductConfigurationInstance(
        CartChangeTransfer $cartChangeTransfer,
        array $params = []
    ): CartChangeTransfer {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productConfigurationInstanceTransfer = $this->productConfigurationInstanceReader->findProductConfigurationInstanceBySku(
                $itemTransfer->getSku()
            );

            if (!$productConfigurationInstanceTransfer) {
                continue;
            }

            $itemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeWithProductConfigurationInstance(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        array $params = []
    ): PersistentCartChangeTransfer {
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            $productConfigurationInstanceTransfer = $this->productConfigurationInstanceReader->findProductConfigurationInstanceBySku(
                $itemTransfer->getSku()
            );

            if (!$productConfigurationInstanceTransfer) {
                continue;
            }

            $itemTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
        }

        return $persistentCartChangeTransfer;
    }
}

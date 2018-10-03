<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\ProductConcretePriceReader;

use Generated\Shared\Transfer\CurrentProductConcretePriceTransfer;
use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Client\PriceProductStorage\PriceProductStorageClient;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductStorageClientInterface;

class ProductConcretePriceReader implements ProductConcretePriceReaderInterface
{
    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductClientInterface $priceProductClient
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductStorageClientInterface $priceProductStorageClient
     */
    public function __construct(
        QuickOrderToPriceProductClientInterface $priceProductClient,
        QuickOrderToPriceProductStorageClientInterface $priceProductStorageClient
    ) {
        $this->priceProductClient = $priceProductClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductConcretePriceTransfer $currentProductConcretePriceTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductConcretePriceTransfer
     */
    public function getProductConcreteSumPrice(CurrentProductConcretePriceTransfer $currentProductConcretePriceTransfer): CurrentProductConcretePriceTransfer
    {
        if ($currentProductConcretePriceTransfer->getIdProductConcrete() === null) {
            return $currentProductConcretePriceTransfer;
        }

        $currentProductPriceTransfer = $currentProductConcretePriceTransfer->getCurrentProductPrice();

        $priceProductTransfers = $this->priceProductStorageClient->getResolvedPriceProductConcreteTransfers(
            $currentProductConcretePriceTransfer->getIdProductConcrete(),
            $currentProductConcretePriceTransfer->getIdProductAbstract()
        );

        $priceProductFilterTransfer = $this->createPriceProductFilterTransferFromCurrentProductPriceTransfer($currentProductPriceTransfer);

        $currentProductPriceTransfer = $this->priceProductClient->calculateProductSumPrice(
            $currentProductPriceTransfer,
            $priceProductFilterTransfer,
            $priceProductTransfers
        );

        return $currentProductConcretePriceTransfer->setCurrentProductPrice($currentProductPriceTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createPriceProductFilterTransferFromCurrentProductPriceTransfer(CurrentProductPriceTransfer $currentProductPriceTransfer): PriceProductFilterTransfer
    {
        return (new PriceProductFilterTransfer())->setQuantity(
            $currentProductPriceTransfer->getQuantity()
        );
    }
}

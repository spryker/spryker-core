<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\ProductConcretePriceReader;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuickOrderProductPriceTransfer;
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
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductPriceTransfer
     */
    public function getQuickOrderProductPrice(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): QuickOrderProductPriceTransfer
    {
        if ($quickOrderProductPriceTransfer->getIdProductConcrete() === null) {
            return $quickOrderProductPriceTransfer;
        }

        $priceProductTransfers = $this->priceProductStorageClient->getPriceProductConcreteTransfers(
            $quickOrderProductPriceTransfer->getIdProductConcrete()
        );

        $priceProductFilterTransfer = $this->createPriceProductFilterTransfer($quickOrderProductPriceTransfer);

        return $this->priceProductClient->calculateQuickOrderProductPrice(
            $quickOrderProductPriceTransfer,
            $priceProductFilterTransfer,
            $priceProductTransfers
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createPriceProductFilterTransfer(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): PriceProductFilterTransfer
    {
        return (new PriceProductFilterTransfer())->setQuantity(
            $quickOrderProductPriceTransfer->getQuantity()
        );
    }
}

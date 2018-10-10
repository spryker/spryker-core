<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Reader;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductStorageClientInterface;

class ProductPriceReader implements ProductPriceReaderInterface
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function getCurrentProductPriceTransfer(ItemTransfer $itemTransfer): CurrentProductPriceTransfer
    {
        $currentProductPriceTransfer = (new CurrentProductPriceTransfer())->setQuantity($itemTransfer->getQuantity());
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setQuantity($itemTransfer->getQuantity());

        if ($itemTransfer->getId() === null) {
            return $currentProductPriceTransfer;
        }

        $priceProductTransfers = $this->priceProductStorageClient->getResolvedPriceProductConcreteTransfers(
            $itemTransfer->getId(),
            $itemTransfer->getIdProductAbstract()
        );

        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        return $currentProductPriceTransfer;
    }
}

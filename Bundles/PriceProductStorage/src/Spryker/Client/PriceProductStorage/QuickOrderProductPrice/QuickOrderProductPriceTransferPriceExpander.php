<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\QuickOrderProductPrice;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuickOrderProductPriceTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToCurrencyClientInterface;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface;

class QuickOrderProductPriceTransferPriceExpander implements QuickOrderProductPriceTransferPriceExpanderInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface
     */
    protected $priceConcreteStorageReader;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface
     */
    protected $priceProductClient;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface $priceConcreteStorageReader
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface $priceProductClient
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToCurrencyClientInterface $currencyClient
     */
    public function __construct(
        PriceConcreteStorageReaderInterface $priceConcreteStorageReader,
        PriceProductStorageToPriceProductInterface $priceProductClient,
        PriceProductStorageToCurrencyClientInterface $currencyClient
    ) {
        $this->priceConcreteStorageReader = $priceConcreteStorageReader;
        $this->priceProductClient = $priceProductClient;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderProductPriceTransfer
     */
    public function expandQuickOrderProductPriceTransferWithPrice(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): QuickOrderProductPriceTransfer
    {
        if ($quickOrderProductPriceTransfer->getIdProductConcrete() === null) {
            return $quickOrderProductPriceTransfer;
        }

        $priceProductTransfers = $this->priceConcreteStorageReader->findPriceProductConcreteTransfers(
            $quickOrderProductPriceTransfer->getIdProductConcrete()
        );

        $priceProductFilterTransfer = new PriceProductFilterTransfer();
        $priceProductFilterTransfer->setQuantity($quickOrderProductPriceTransfer->getQuantity());

        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter($priceProductTransfers, $priceProductFilterTransfer);

        $quickOrderProductPriceTransfer->setCurrentProductPrice($currentProductPriceTransfer);
        $quickOrderProductPriceTransfer->setTotal($currentProductPriceTransfer->getPrice() * $quickOrderProductPriceTransfer->getQuantity());
        $quickOrderProductPriceTransfer->setCurrency($this->currencyClient->getCurrent());

        return $quickOrderProductPriceTransfer;
    }
}

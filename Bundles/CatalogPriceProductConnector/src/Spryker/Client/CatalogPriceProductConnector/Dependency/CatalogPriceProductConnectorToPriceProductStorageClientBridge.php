<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector\Dependency;

use Generated\Shared\Transfer\PriceProductStorageTransfer;

class CatalogPriceProductConnectorToPriceProductStorageClientBridge implements CatalogPriceProductConnectorToPriceProductStorageClientInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @param \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface $priceProductStorageClient
     */
    public function __construct($priceProductStorageClient)
    {
        $this->priceProductStorageClient = $priceProductStorageClient;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function getPriceProductAbstractStorageTransfer(int $idProductAbstract): ?PriceProductStorageTransfer
    {
        return $this->priceProductStorageClient->getPriceProductAbstractStorageTransfer($idProductAbstract);
    }
}

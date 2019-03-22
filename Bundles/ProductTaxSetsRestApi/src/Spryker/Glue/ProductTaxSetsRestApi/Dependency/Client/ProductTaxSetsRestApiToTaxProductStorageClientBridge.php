<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client;

use Generated\Shared\Transfer\TaxProductStorageTransfer;

class ProductTaxSetsRestApiToTaxProductStorageClientBridge implements ProductTaxSetsRestApiToTaxProductStorageClientInterface
{
    /**
     * @var \Spryker\CLient\TaxProductStorage\TaxProductStorageCLientInterface
     */
    protected $taxProductStorageCLient;

    /**
     * @param \Spryker\CLient\TaxProductStorage\TaxProductStorageCLientInterface $taxProductStorageClient
     */
    public function __construct($taxProductStorageClient)
    {
        $this->taxProductStorageCLient = $taxProductStorageClient;
    }

    /**
     * @param string $productAbstractSku
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer|null
     */
    public function findTaxProductStorage(string $productAbstractSku): ?TaxProductStorageTransfer
    {
        return $this->taxProductStorageCLient->findTaxProductStorage($productAbstractSku);
    }
}

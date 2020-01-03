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
     * @var \Spryker\Client\TaxProductStorage\TaxProductStorageClientInterface
     */
    protected $taxProductStorageClient;

    /**
     * @param \Spryker\Client\TaxProductStorage\TaxProductStorageClientInterface $taxProductStorageClient
     */
    public function __construct($taxProductStorageClient)
    {
        $this->taxProductStorageClient = $taxProductStorageClient;
    }

    /**
     * @param string $productAbstractSku
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer|null
     */
    public function findTaxProductStorageByProductAbstractSku(string $productAbstractSku): ?TaxProductStorageTransfer
    {
        return $this->taxProductStorageClient->findTaxProductStorageByProductAbstractSku($productAbstractSku);
    }
}

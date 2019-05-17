<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client;

use Generated\Shared\Transfer\TaxSetStorageTransfer;

class ProductTaxSetsRestApiToTaxStorageClientBridge implements ProductTaxSetsRestApiToTaxStorageClientInterface
{
    /**
     * @var \Spryker\Client\TaxStorage\TaxStorageClientInterface
     */
    protected $taxStorageClient;

    /**
     * @param \Spryker\Client\TaxStorage\TaxStorageClientInterface $taxStorageClient
     */
    public function __construct($taxStorageClient)
    {
        $this->taxStorageClient = $taxStorageClient;
    }

    /**
     * @param int $idTaxSet
     *
     * @return \Generated\Shared\Transfer\TaxSetStorageTransfer|null
     */
    public function findTaxSetStorageByIdTaxSet(int $idTaxSet): ?TaxSetStorageTransfer
    {
        return $this->taxStorageClient->findTaxSetStorageByIdTaxSet($idTaxSet);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxSetsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;

class TaxSetsRestApiToTaxProductConnectorClientBridge implements TaxSetsRestApiToTaxProductConnectorClientInterface
{
    /**
     * @var \Spryker\Client\TaxProductConnector\TaxProductConnectorClientInterface
     */
    protected $taxProductConnectorClient;

    /**
     * @param \Spryker\Client\TaxProductConnector\TaxProductConnectorClientInterface $taxProductConnectorClient
     */
    public function __construct($taxProductConnectorClient)
    {
        $this->taxProductConnectorClient = $taxProductConnectorClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetResponseTransfer
     */
    public function getTaxSetForProductAbstract(ProductAbstractTransfer $productAbstractTransfer): TaxSetResponseTransfer
    {
        return $this->taxProductConnectorClient->getTaxSetForProductAbstract($productAbstractTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxProductConnector\Zed;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;
use Spryker\Client\TaxProductConnector\Dependency\Client\TaxProductConnectorToZedRequestClientInterface;

class TaxProductConnectorStub implements TaxProductConnectorStubInterface
{
    /**
     * @var \Spryker\Client\TaxProductConnector\Dependency\Client\TaxProductConnectorToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\TaxProductConnector\Dependency\Client\TaxProductConnectorToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(TaxProductConnectorToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetResponseTransfer
     */
    public function getTaxSetForProductAbstract(ProductAbstractTransfer $productAbstractTransfer): TaxSetResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\TaxSetResponseTransfer $taxSetResponseTransfer */
        $taxSetResponseTransfer = $this->zedRequestClient->call('/tax-product-connector/gateway/get-tax-set-for-product-abstract', $productAbstractTransfer);

        return $taxSetResponseTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface;
use Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig;

class ProductAbstractTaxReader implements ProductAbstractTaxReaderInterface
{
    /**
     * @var \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface
     */
    protected $taxProductConnectorRepository;

    /**
     * @param \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface $taxProductConnectorRepository
     */
    public function __construct(TaxProductConnectorRepositoryInterface $taxProductConnectorRepository)
    {
        $this->taxProductConnectorRepository = $taxProductConnectorRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetResponseTransfer
     */
    public function getTaxSetByProductAbstract(ProductAbstractTransfer $productAbstractTransfer): TaxSetResponseTransfer
    {
        $taxSetResponse = new TaxSetResponseTransfer();
        $taxRateSet = $this->taxProductConnectorRepository->findTaxSetByProductAbstractSku($productAbstractTransfer->getSku());

        if ($taxRateSet === null) {
            $taxSetResponse->setIsSuccess(false);
            $taxSetResponse->setError(
                sprintf(
                    TaxProductConnectorConfig::EXCEPTION_MESSAGE_TAX_SET_NOT_FOUND_FOR_ABSTRACT,
                    $productAbstractTransfer->getIdProductAbstract()
                )
            );

            return $taxSetResponse;
        }

        $taxSetResponse->setTaxRateSet($taxRateSet);
        $taxSetResponse->setIsSuccess(true);

        return $taxSetResponse;
    }
}

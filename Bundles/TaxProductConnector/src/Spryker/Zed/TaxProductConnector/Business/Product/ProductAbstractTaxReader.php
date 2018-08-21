<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface;

class ProductAbstractTaxReader implements ProductAbstractTaxReaderInterface
{
    /**
     * @var \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface
     */
    protected $taxRepository;

    /**
     * @param \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface $taxRepositoryInterface
     */
    public function __construct(TaxProductConnectorRepositoryInterface $taxRepositoryInterface)
    {
        $this->taxRepository = $taxRepositoryInterface;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetResponseTransfer
     */
    public function readTaxSetByProductAbstract(ProductAbstractTransfer $productAbstractTransfer): TaxSetResponseTransfer
    {
        $taxSetResponse = new TaxSetResponseTransfer();
        $taxRateSet = $this->taxRepository->getTaxSetByProductAbstractSku($productAbstractTransfer->getSku());

        if ($taxRateSet === null) {
            $taxSetResponse->setIsSuccess(false);
            $taxSetResponse->setError(
                sprintf(
                    'Could not get tax set, product abstract with id "%d" not found.',
                    $productAbstractTransfer->getIdProductAbstract()
                )
            );
        }

        $taxSetResponse->setTaxRateSet($taxRateSet);
        $taxSetResponse->setIsSuccess(true);

        return $taxSetResponse;
    }
}

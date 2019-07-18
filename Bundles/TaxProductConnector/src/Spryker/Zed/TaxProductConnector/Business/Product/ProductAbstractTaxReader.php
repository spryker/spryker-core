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
    protected const EXCEPTION_MESSAGE_TAX_SET_NOT_FOUND_FOR_ABSTRACT = 'Could not get tax set, product abstract with id "%d" not found.';

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
        $taxSetResponseTransfer = new TaxSetResponseTransfer();
        $taxSetTransfer = $this->taxProductConnectorRepository->findTaxSetByProductAbstractSku($productAbstractTransfer->getSku());

        if ($taxSetTransfer === null) {
            $taxSetResponseTransfer->setIsSuccess(false);
            $taxSetResponseTransfer->setError(
                sprintf(
                    static::EXCEPTION_MESSAGE_TAX_SET_NOT_FOUND_FOR_ABSTRACT,
                    $productAbstractTransfer->getIdProductAbstract()
                )
            );

            return $taxSetResponseTransfer;
        }

        $taxSetResponseTransfer->setTaxSet($taxSetTransfer);
        $taxSetResponseTransfer->setIsSuccess(true);

        return $taxSetResponseTransfer;
    }
}

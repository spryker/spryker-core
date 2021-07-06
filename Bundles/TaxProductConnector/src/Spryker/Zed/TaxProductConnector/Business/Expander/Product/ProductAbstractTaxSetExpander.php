<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Expander\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface;

class ProductAbstractTaxSetExpander implements ProductAbstractTaxSetExpanderInterface
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
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expand(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $productAbstractTransfer->requireIdProductAbstract();

        $taxSetTransfer = $this->taxProductConnectorRepository->findByIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

        if ($taxSetTransfer === null) {
            return $productAbstractTransfer;
        }

        return $productAbstractTransfer->setIdTaxSet($taxSetTransfer->getIdTaxSet());
    }
}

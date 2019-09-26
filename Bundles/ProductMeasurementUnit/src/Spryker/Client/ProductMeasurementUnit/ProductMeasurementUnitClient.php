<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnit;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductMeasurementUnit\ProductMeasurementUnitFactory getFactory()
 */
class ProductMeasurementUnitClient extends AbstractClient implements ProductMeasurementUnitClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandSingleItemQuantitySalesUnitForPersistentCartChange(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitExpander()
            ->expandSingleItemQuantitySalesUnitForPersistentCartChange($cartChangeTransfer, $params);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @throws \Spryker\Client\ProductMeasurementUnit\Exception\InvalidItemCountException
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandSingleItemQuantitySalesUnitForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitExpander()
            ->expandSingleItemQuantitySalesUnitForCartChangeRequest($cartChangeTransfer, $params);
    }
}

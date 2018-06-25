<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductPackagingUnit;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductPackagingUnit\ProductPackagingUnitFactory getFactory()
 */
class ProductPackagingUnitClient extends AbstractClient implements ProductPackagingUnitClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandproductPackagingUnitQuantityForPersistentCartChange(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        return $this->getFactory()
            ->createProductPackagingUnitQuantityExpander()
            ->expandproductPackagingUnitQuantityForPersistentCartChange($persistentCartChangeTransfer, $params);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @throws \Spryker\Client\ProductPackagingUnit\Exception\InvalidItemCountException
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandproductPackagingUnitQuantityForCartChangeRequest(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        return $this->getFactory()
            ->createProductPackagingUnitQuantityExpander()
            ->expandproductPackagingUnitQuantityForCartChangeRequest($cartChangeTransfer, $params);
    }
}

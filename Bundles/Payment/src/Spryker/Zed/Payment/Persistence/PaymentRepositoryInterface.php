<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\SalesPaymentMethodTypeCollectionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

interface PaymentRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\SalesPaymentMethodTypeCollectionTransfer
     */
    public function getSalesPaymentMethodTypesCollection(): SalesPaymentMethodTypeCollectionTransfer;

    /**
     * @param int $idPaymentMethod
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    public function findPaymentMethodById(int $idPaymentMethod): ?PaymentMethodTransfer;

    /**
     * @param int $idPaymentMethod
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdPaymentMethod(int $idPaymentMethod): StoreRelationTransfer;
}

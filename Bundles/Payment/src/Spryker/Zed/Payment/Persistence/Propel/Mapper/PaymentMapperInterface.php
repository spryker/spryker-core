<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType;

interface PaymentMapperInterface
{
    /**
     * @param \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType $salesPaymentMethodTypeEntity
     * @param \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer
     */
    public function mapSalesPaymentMethodTypeTransfer(
        SpySalesPaymentMethodType $salesPaymentMethodTypeEntity,
        SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
    ): SalesPaymentMethodTypeTransfer;
}

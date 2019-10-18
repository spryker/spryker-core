<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCheckoutDataTransfer;

interface PaymentMethodMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[] $restPaymentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    public function mapRestCheckoutDataTransferToRestPaymentMethodsAttributesTransfers(
        RestCheckoutDataTransfer $restCheckoutDataTransfer,
        array $restPaymentMethodsAttributesTransfers = []
    ): array;
}

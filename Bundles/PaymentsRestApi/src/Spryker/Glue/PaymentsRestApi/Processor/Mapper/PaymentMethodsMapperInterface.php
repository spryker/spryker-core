<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Mapper;

interface PaymentMethodsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer[] $paymentProviderTransfers
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[] $restPaymentMethodsAttributesTransfers
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    public function mapPaymentProviderTransfersToRestPaymentMethodsAttributesTransfers(
        array $paymentProviderTransfers,
        array $restPaymentMethodsAttributesTransfers = []
    ): array;
}

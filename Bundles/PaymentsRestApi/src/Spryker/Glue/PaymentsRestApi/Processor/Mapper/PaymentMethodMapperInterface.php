<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Mapper;

interface PaymentMethodMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer[] $paymentProviderTransfers
     *
     * @return \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer[]
     */
    public function mapPaymentProviderTransfersToRestPaymentMethodsAttributesTransfers(
        array $paymentProviderTransfers
    ): array;
}

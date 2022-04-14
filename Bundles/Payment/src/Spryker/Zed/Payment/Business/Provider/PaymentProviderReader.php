<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Provider;

use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentProviderReader implements PaymentProviderReaderInterface
{
    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    private $paymentRepository;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer|null
     */
    public function findPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): ?PaymentProviderTransfer
    {
        $paymentProviderTransfer->requirePaymentProviderKey();

        return $this->paymentRepository
            ->findPaymentProviderByKey($paymentProviderTransfer->getPaymentProviderKey());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;

class TransferEndpointReader implements TransferEndpointReaderInterface
{
    /**
     * @var string
     */
    protected const PAYMENT_METHOD_ENDPOINT_NAME_TRANSFER = 'transfer';

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\PaymentMethodReaderInterface
     */
    protected PaymentMethodReaderInterface $paymentMethodReader;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\PaymentMethodReaderInterface $paymentMethodReader
     */
    public function __construct(PaymentMethodReaderInterface $paymentMethodReader)
    {
        $this->paymentMethodReader = $paymentMethodReader;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string|null
     */
    public function getTransferEndpointUrl(OrderTransfer $orderTransfer): ?string
    {
        $paymentMethodTransfer = $this->paymentMethodReader->getPaymentMethodForOrder($orderTransfer);
        if (!$paymentMethodTransfer->getPaymentMethodAppConfiguration()) {
            return null;
        }

        $paymentMethodAppConfigurationTransfer = $paymentMethodTransfer->getPaymentMethodAppConfigurationOrFail();
        $endpointTransfers = $paymentMethodAppConfigurationTransfer->getEndpoints();

        $endpointPath = $this->findTransferEndpointPath($endpointTransfers);
        if (!$endpointPath) {
            return null;
        }

        return $this->buildEndpointUrl($paymentMethodAppConfigurationTransfer->getBaseUrlOrFail(), $endpointPath);
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\EndpointTransfer> $endpointTransfers
     *
     * @return string|null
     */
    protected function findTransferEndpointPath(ArrayObject $endpointTransfers): ?string
    {
        foreach ($endpointTransfers as $endpointTransfer) {
            if ($endpointTransfer->getName() === static::PAYMENT_METHOD_ENDPOINT_NAME_TRANSFER) {
                return $endpointTransfer->getPath();
            }
        }

        return null;
    }

    /**
     * @param string $endpointBaseUrl
     * @param string $endpointPath
     *
     * @return string
     */
    protected function buildEndpointUrl(
        string $endpointBaseUrl,
        string $endpointPath
    ): string {
        return sprintf('%s%s', $endpointBaseUrl, $endpointPath);
    }
}

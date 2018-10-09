<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Ratepay\Business\Api\Builder;

use Spryker\Zed\Ratepay\Business\Api\Constants;

class Head extends AbstractBuilder implements BuilderInterface
{
    public const ROOT_TAG = 'head';

    /**
     * @return array
     */
    public function buildData()
    {
        $return = [
            'system-id' => $this->requestTransfer->getHead()->getSystemId(),
            'transaction-id' => $this->requestTransfer->getHead()->getTransactionId(),
            'transaction-short-id' => $this->requestTransfer->getHead()->getTransactionShortId(),
            'credential' => [
                'profile-id' => $this->requestTransfer->getHead()->getProfileId(),
                'securitycode' => $this->requestTransfer->getHead()->getSecurityCode(),
            ],
            'customer-device' => [
                'device-token' => $this->requestTransfer->getHead()->getDeviceFingerprint(),
            ],
            'external' => [
                'merchant-consumer-id' => $this->requestTransfer->getHead()->getCustomerId(),
            ],
            'meta' => [
                'systems' => [
                    'system' => [
                        '@name' => Constants::CLIENT_NAME,
                        '@version' => Constants::CLIENT_VERSION,
                    ],
                ],
            ],
            'operation' => $this->requestTransfer->getHead()->getOperation(),
        ];

        if ($this->requestTransfer->getHead()->getOperationSubstring() !== null) {
            $return['operation'] = [
                '@subtype' => $this->requestTransfer->getHead()->getOperationSubstring(),
                '#' => $this->requestTransfer->getHead()->getOperation(),
            ];
        }

        if ($this->requestTransfer->getHead()->getExternalOrderId() !== null) {
            $return['external'] = [
                'order-id' => $this->requestTransfer->getHead()->getExternalOrderId(),
            ];
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getRootTag()
    {
        return static::ROOT_TAG;
    }

    /**
     * @param string $operation
     *
     * @return void
     */
    public function setOperation($operation)
    {
        $this->requestTransfer->getHead()->setOperation($operation);
    }

    /**
     * @param string $subOperation
     *
     * @return void
     */
    public function setOperationSubstring($subOperation)
    {
        $this->requestTransfer->getHead()->setOperationSubstring($subOperation);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Validator;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageValidationResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Store\Business\Model\StoreReaderInterface;

class MessageValidator implements MessageValidatorInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const VALIDATION_ERROR_STORE_REFERENCE_ERROR = 'Invalid storeReference in message "%s"';

    /**
     * @var \Spryker\Zed\Store\Business\Model\StoreReaderInterface
     */
    protected $storeReader;

    /**
     * @var bool
     */
    protected $isDynamicMultiStoreEnabled;

    /**
     * @param \Spryker\Zed\Store\Business\Model\StoreReaderInterface $storeReader
     */
    public function __construct(StoreReaderInterface $storeReader, bool $isDynamicMultiStoreEnabled)
    {
        $this->storeReader = $storeReader;
        $this->isDynamicMultiStoreEnabled = $isDynamicMultiStoreEnabled;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageValidationResponseTransfer
     */
    public function validate(TransferInterface $messageTransfer): MessageValidationResponseTransfer
    {
        $messageValidationResponse = new MessageValidationResponseTransfer();

        if ($this->isDynamicMultiStoreEnabled) {
            return $messageValidationResponse->setIsValid(true);
        }

        if (!method_exists($messageTransfer, 'getMessageAttributes')) {
            $this->getLogger()->error(
                sprintf('MessageTransfer `%s` must have method getMessageAttributes().', get_class($messageTransfer)),
                [
                    'message' => $messageTransfer->toArray(),
                ],
            );

            return $messageValidationResponse->setIsValid(false);
        }

        $messageAttributesTransfer = $messageTransfer->getMessageAttributes();

        if (!$messageAttributesTransfer instanceof MessageAttributesTransfer) {
            $this->getLogger()->error(
                'MessageAttributes has invalid type.',
                [
                    'message' => $messageTransfer->toArray(),
                ],
            );

            return $messageValidationResponse->setIsValid(false);
        }

        $storeReference = $messageAttributesTransfer->getStoreReference();

        if ($storeReference === null) {
            $this->getLogger()->error('StoreReference cannot be `null`.');

            return $messageValidationResponse->setIsValid(false);
        }

        $allStores = $this->storeReader->getAllStores();

        foreach ($allStores as $store) {
            if ($store->getStoreReference() === $storeReference) {
                return $messageValidationResponse->setIsValid(true);
            }
        }

        $this->getLogger()->error(
            sprintf(static::VALIDATION_ERROR_STORE_REFERENCE_ERROR, get_class($messageTransfer)),
            [
                'message' => $messageTransfer->toArray(),
                'storeReference' => $storeReference,
            ],
        );

        return $messageValidationResponse->setIsValid(false);
    }
}

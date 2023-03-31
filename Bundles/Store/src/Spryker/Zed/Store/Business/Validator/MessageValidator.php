<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Validator;

use Generated\Shared\Transfer\MessageValidationResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException;
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
     * @var string
     */
    protected string $storeName;

    /**
     * @param \Spryker\Zed\Store\Business\Model\StoreReaderInterface $storeReader
     * @param string $storeName
     */
    public function __construct(StoreReaderInterface $storeReader, string $storeName)
    {
        $this->storeReader = $storeReader;
        $this->storeName = $storeName;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageValidationResponseTransfer
     */
    public function validate(TransferInterface $messageTransfer): MessageValidationResponseTransfer
    {
        $messageValidationResponse = new MessageValidationResponseTransfer();

        try {
            $storeReference = $this->storeReader->getStoreByName($this->storeName)->getStoreReference();
        } catch (StoreReferenceNotFoundException $exception) {
            $this->getLogger()->error('Store reference map is not configured');

            return $messageValidationResponse->setIsValid(false);
        }

        /** @phpstan-var \Generated\Shared\Transfer\MessageBrokerTestMessageTransfer $messageTransfer */
        if ($storeReference !== $messageTransfer->getMessageAttributesOrFail()->getStoreReference()) {
            $this->getLogger()->error(
                sprintf(static::VALIDATION_ERROR_STORE_REFERENCE_ERROR, get_class($messageTransfer)),
                [
                    'message' => $messageTransfer->toArray(),
                    'storeReference' => $storeReference,
                ],
            );

            return $messageValidationResponse->setIsValid(false);
        }

        return $messageValidationResponse->setIsValid(true);
    }
}

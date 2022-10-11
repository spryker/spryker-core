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
     * @param \Spryker\Zed\Store\Business\Model\StoreReaderInterface $storeReader
     */
    public function __construct(StoreReaderInterface $storeReader)
    {
        $this->storeReader = $storeReader;
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
            $storeReference = $this->storeReader->getCurrentStore()->getStoreReference();
        } catch (StoreReferenceNotFoundException $exception) {
            $this->getLogger()->error('Store reference map is not configured');

            return $messageValidationResponse->setIsValid(false);
        }

        if ($storeReference !== $messageTransfer->getMessageAttributes()->getStoreReference()) {
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

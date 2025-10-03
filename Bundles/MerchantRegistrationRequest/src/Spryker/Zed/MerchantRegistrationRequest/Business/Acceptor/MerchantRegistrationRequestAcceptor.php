<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Business\Acceptor;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantRegistrationResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRegistrationRequest\Business\Creator\MerchantCreatorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Creator\MerchantUserCreatorInterface;
use Spryker\Zed\MerchantRegistrationRequest\Business\Exception\InvalidSaveMerchantTransactionMaxAttemptsConfiguration;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;
use Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestEntityManagerInterface;
use Throwable;

class MerchantRegistrationRequestAcceptor implements MerchantRegistrationRequestAcceptorInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_CANNOT_BE_ACCEPTED = 'merchant_registration_request.error.merchant_cannot_be_accepted';

    public function __construct(
        protected MerchantCreatorInterface $merchantCreator,
        protected MerchantUserCreatorInterface $merchantUserCreator,
        protected MerchantRegistrationRequestEntityManagerInterface $merchantRegistrationRequestEntityManager,
        protected MerchantRegistrationRequestConfig $merchantRegistrationRequestConfig
    ) {
    }

    /**
     * @throws \Throwable
     * @throws \Spryker\Zed\MerchantRegistrationRequest\Business\Exception\InvalidSaveMerchantTransactionMaxAttemptsConfiguration
     */
    public function acceptMerchantRegistrationRequest(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationResponseTransfer {
        $merchantRegistrationResponseTransfer = (new MerchantRegistrationResponseTransfer())
            ->setIsSuccess(true);

        if (!in_array($merchantRegistrationRequestTransfer->getStatus(), $this->merchantRegistrationRequestConfig->getAcceptableStatuses())) {
            return $merchantRegistrationResponseTransfer->setIsSuccess(false)
                ->addError((new ErrorTransfer())->setMessage(static::GLOSSARY_KEY_MERCHANT_CANNOT_BE_ACCEPTED))
                ->setMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
        }

        $maxAttempts = $this->merchantRegistrationRequestConfig->getSaveMerchantTransactionMaxAttempts();

        if ($maxAttempts <= 0) {
            throw new InvalidSaveMerchantTransactionMaxAttemptsConfiguration();
        }

        while ($maxAttempts) {
            $maxAttempts--;

            try {
                $merchantRegistrationRequestTransfer = $this->getTransactionHandler()
                    ->handleTransaction(function () use ($merchantRegistrationRequestTransfer) {
                        return $this->executeAcceptMerchantRegistrationRequestTransaction($merchantRegistrationRequestTransfer);
                    });

                break;
            } catch (Throwable $e) {
                if ($maxAttempts <= 0) {
                    throw $e;
                }
            }
        }

        return $merchantRegistrationResponseTransfer->setMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }

    protected function executeAcceptMerchantRegistrationRequestTransaction(
        MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer
    ): MerchantRegistrationRequestTransfer {
        $merchantTransfer = $this->merchantCreator->createMerchant($merchantRegistrationRequestTransfer);
        $this->merchantUserCreator->createMerchantUser($merchantRegistrationRequestTransfer, $merchantTransfer);
        $merchantRegistrationRequestTransfer->setStatus(MerchantRegistrationRequestConfig::STATUS_ACCEPTED);

        return $this->merchantRegistrationRequestEntityManager
            ->updateMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Validator\Customer;

use DateTime;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Business\Validator\CodeValidatorInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface;
use Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

class CustomerCodeValidator implements CodeValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ATTEMPTS_EXCEEDED = 'multi_factor_auth.error.attempts_exceeded';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_MULTI_FACTOR_AUTH_CODE = 'multi_factor_auth.error.invalid_code';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_EXPIRED_MULTI_FACTOR_AUTH_CODE = 'multi_factor_auth.error.expired_code';

    /**
     * @var string
     */
    protected const GLOSSARY_PARAM_REMAINING_ATTEMPTS = '%remainingAttempts%';

    /**
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface $repository
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig $config
     */
    public function __construct(
        protected MultiFactorAuthRepositoryInterface $repository,
        protected MultiFactorAuthEntityManagerInterface $entityManager,
        protected MultiFactorAuthToGlossaryFacadeInterface $glossaryFacade,
        protected MultiFactorAuthConfig $config
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validate(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthValidationResponseTransfer
    {
        $validMultiFactorAuthCodeTransfer = $this->repository->getCustomerCode($multiFactorAuthTransfer);
        $this->entityManager->saveCustomerMultiFactorAuthCodeAttempt($validMultiFactorAuthCodeTransfer);
        $multiFactorAuthValidationResponseTransfer = new MultiFactorAuthValidationResponseTransfer();

        if (
            $validMultiFactorAuthCodeTransfer->getCode() === $multiFactorAuthTransfer->getMultiFactorAuthCodeOrFail()->getCode()
            && $this->isCodeExpired($validMultiFactorAuthCodeTransfer) === false
            && $validMultiFactorAuthCodeTransfer->getStatus() === MultiFactorAuthConstants::CODE_UNVERIFIED
        ) {
            $multiFactorAuthTransfer->getMultiFactorAuthCodeOrFail()->setStatus(MultiFactorAuthConstants::CODE_VERIFIED);
            $this->entityManager->updateCustomerCode($multiFactorAuthTransfer);

            if ($multiFactorAuthTransfer->getStatus() === MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION) {
                $multiFactorAuthTransfer->setStatus(MultiFactorAuthConstants::STATUS_ACTIVE);
                $this->entityManager->saveCustomerMultiFactorAuth($multiFactorAuthTransfer);
            }

            $multiFactorAuthValidationResponseTransfer->setStatus(MultiFactorAuthConstants::CODE_VERIFIED);

            return $multiFactorAuthValidationResponseTransfer;
        }

        $validMultiFactorAuthCodeTransfer->setAttempts(
            $this->getAttemptsCount($validMultiFactorAuthCodeTransfer),
        );

        if ($this->hasReachedMaxAttempts($validMultiFactorAuthCodeTransfer)) {
            return $this->handleMaxAttemptsReached($validMultiFactorAuthCodeTransfer, $multiFactorAuthTransfer);
        }

        return $this->handleInvalidAttempt($validMultiFactorAuthCodeTransfer, $multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return int
     */
    protected function getAttemptsCount(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): int
    {
        return $this->repository->getCustomerCodeEnteringAttemptsCount($multiFactorAuthCodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return bool
     */
    protected function hasReachedMaxAttempts(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): bool
    {
        return $multiFactorAuthCodeTransfer->getAttempts() === $this->config->getCustomerAttemptsLimit();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    protected function handleMaxAttemptsReached(
        MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer,
        MultiFactorAuthTransfer $multiFactorAuthTransfer
    ): MultiFactorAuthValidationResponseTransfer {
        $message = $this->glossaryFacade->translate(static::GLOSSARY_KEY_ATTEMPTS_EXCEEDED);

        return $this->processMultiFactorAuthFailure(
            $multiFactorAuthCodeTransfer,
            $multiFactorAuthTransfer,
            MultiFactorAuthConstants::CODE_BLOCKED,
            $message,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    protected function handleInvalidAttempt(
        MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer,
        MultiFactorAuthTransfer $multiFactorAuthTransfer
    ): MultiFactorAuthValidationResponseTransfer {
        $message = $this->glossaryFacade->translate(
            static::GLOSSARY_KEY_INVALID_MULTI_FACTOR_AUTH_CODE,
            [static::GLOSSARY_PARAM_REMAINING_ATTEMPTS => $this->config->getCustomerAttemptsLimit() - $multiFactorAuthCodeTransfer->getAttempts()],
        );
        $status = MultiFactorAuthConstants::CODE_UNVERIFIED;

        if ($this->isCodeExpired($multiFactorAuthCodeTransfer) === true) {
            $message = $this->glossaryFacade->translate(static::GLOSSARY_KEY_EXPIRED_MULTI_FACTOR_AUTH_CODE);
            $status = MultiFactorAuthConstants::CODE_BLOCKED;
        }

        return $this->processMultiFactorAuthFailure(
            $multiFactorAuthCodeTransfer,
            $multiFactorAuthTransfer,
            $status,
            $message,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     * @param int $status
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    protected function processMultiFactorAuthFailure(
        MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer,
        MultiFactorAuthTransfer $multiFactorAuthTransfer,
        int $status,
        string $message
    ): MultiFactorAuthValidationResponseTransfer {
        $multiFactorAuthCodeTransfer->setStatus($status);
        $multiFactorAuthTransfer->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);

        $this->entityManager->updateCustomerCode($multiFactorAuthTransfer);

        return (new MultiFactorAuthValidationResponseTransfer())
            ->setStatus($status)
            ->setMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return bool
     */
    protected function isCodeExpired(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): bool
    {
        return new DateTime($multiFactorAuthCodeTransfer->getExpirationDateOrFail()) < new DateTime();
    }
}

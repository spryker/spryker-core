<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Validator\User;

use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Zed\MultiFactorAuth\Business\Validator\AbstractCodeValidator;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface;
use Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface;
use Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface;

class UserCodeValidator extends AbstractCodeValidator
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface $repository
     * @param \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig $config
     */
    public function __construct(
        protected MultiFactorAuthRepositoryInterface $repository,
        protected MultiFactorAuthEntityManagerInterface $entityManager,
        MultiFactorAuthToGlossaryFacadeInterface $glossaryFacade,
        protected MultiFactorAuthConfig $config
    ) {
        parent::__construct($glossaryFacade);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer
     */
    protected function getValidCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): MultiFactorAuthCodeTransfer
    {
        return $this->repository->getUserCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return void
     */
    protected function saveMultiFactorAuthCodeAttempt(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): void
    {
        $this->entityManager->saveUserMultiFactorAuthCodeAttempt($multiFactorAuthCodeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    protected function updateCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        $this->entityManager->updateUserCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return void
     */
    protected function saveMultiFactorAuth(MultiFactorAuthTransfer $multiFactorAuthTransfer): void
    {
        $this->entityManager->saveUserMultiFactorAuth($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer
     *
     * @return int
     */
    protected function getCodeEnteringAttemptsCount(MultiFactorAuthCodeTransfer $multiFactorAuthCodeTransfer): int
    {
        return $this->repository->getUserCodeEnteringAttemptsCount($multiFactorAuthCodeTransfer);
    }

    /**
     * @return int
     */
    protected function getAttemptsLimit(): int
    {
        return $this->config->getUserAttemptsLimit();
    }
}

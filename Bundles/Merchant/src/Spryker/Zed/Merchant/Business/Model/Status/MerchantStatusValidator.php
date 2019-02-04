<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model\Status;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Business\Exception\MerchantStatusTransitionNotAllowedException;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantStatusValidator implements MerchantStatusValidatorInterface
{
    protected const ERROR_TRANSITION_TO_STATUS_NOT_ALLOWED = 'Transition to status \`%s\` is not allowed.';

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\Merchant\MerchantConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $repository
     * @param \Spryker\Zed\Merchant\MerchantConfig $config
     */
    public function __construct(MerchantRepositoryInterface $repository, MerchantConfig $config)
    {
        $this->repository = $repository;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @throws \Spryker\Zed\Merchant\Business\Exception\MerchantStatusTransitionNotAllowedException
     *
     * @return void
     */
    public function validateTransitionToStatus(MerchantTransfer $merchantTransfer): void
    {
        $existingMerchant = $this->repository->findMerchantById($merchantTransfer->getIdMerchant());

        $statusTree = $this->config->getStatusTree();
        if ($existingMerchant !== null
            && $merchantTransfer->getStatus() !== $existingMerchant->getStatus()
            && !in_array($merchantTransfer->getStatus(), $statusTree[$existingMerchant->getStatus()])
        ) {
            throw new MerchantStatusTransitionNotAllowedException(sprintf(static::ERROR_TRANSITION_TO_STATUS_NOT_ALLOWED, $merchantTransfer->getStatus()));
        }
    }
}

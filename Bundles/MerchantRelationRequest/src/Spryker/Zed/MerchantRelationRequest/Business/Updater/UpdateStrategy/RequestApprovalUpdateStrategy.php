<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig as SharedMerchantRelationRequestConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationshipCreatorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationRequestNotFoundException;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface;

class RequestApprovalUpdateStrategy implements MerchantRelationRequestUpdaterStrategyInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface
     */
    protected MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface
     */
    protected MerchantRelationRequestReaderInterface $merchantRelationRequestReader;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig
     */
    protected MerchantRelationRequestConfig $merchantRelationRequestConfig;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationshipCreatorInterface
     */
    protected MerchantRelationshipCreatorInterface $merchantRelationshipCreator;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface $merchantRelationRequestReader
     * @param \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig $merchantRelationRequestConfig
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Creator\MerchantRelationshipCreatorInterface $merchantRelationshipCreator
     */
    public function __construct(
        MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager,
        MerchantRelationRequestReaderInterface $merchantRelationRequestReader,
        MerchantRelationRequestConfig $merchantRelationRequestConfig,
        MerchantRelationshipCreatorInterface $merchantRelationshipCreator
    ) {
        $this->merchantRelationRequestEntityManager = $merchantRelationRequestEntityManager;
        $this->merchantRelationRequestReader = $merchantRelationRequestReader;
        $this->merchantRelationRequestConfig = $merchantRelationRequestConfig;
        $this->merchantRelationshipCreator = $merchantRelationshipCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        return $merchantRelationRequestTransfer->getStatusOrFail() === SharedMerchantRelationRequestConfig::STATUS_APPROVED;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @throws \Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationRequestNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function execute(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer {
        $persistedMerchantRelationRequest = $this->merchantRelationRequestReader->findMerchantRelationRequestByUuid(
            $merchantRelationRequestTransfer->getUuidOrFail(),
        );

        if (!$persistedMerchantRelationRequest) {
            throw new MerchantRelationRequestNotFoundException();
        }

        foreach ($this->merchantRelationRequestConfig->getModifiableFieldsAllowedForApproval() as $allowedField) {
            $persistedMerchantRelationRequest->offsetSet($allowedField, $merchantRelationRequestTransfer->offsetGet($allowedField));
        }

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($persistedMerchantRelationRequest) {
                return $this->executeUpdateTransaction($persistedMerchantRelationRequest);
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    protected function executeUpdateTransaction(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): MerchantRelationRequestTransfer
    {
        $merchantRelationRequestTransfer = $this->merchantRelationRequestEntityManager
            ->updateMerchantRelationRequest($merchantRelationRequestTransfer);

        $this->merchantRelationshipCreator->createMerchantRelationships($merchantRelationRequestTransfer);

        return $merchantRelationRequestTransfer;
    }
}

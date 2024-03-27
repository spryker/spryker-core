<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Updater\UpdateStrategy;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig as SharedMerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationRequestNotFoundException;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface;

class RequestRejectionUpdateStrategy implements MerchantRelationRequestUpdaterStrategyInterface
{
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
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface $merchantRelationRequestReader
     * @param \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig $merchantRelationRequestConfig
     */
    public function __construct(
        MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager,
        MerchantRelationRequestReaderInterface $merchantRelationRequestReader,
        MerchantRelationRequestConfig $merchantRelationRequestConfig
    ) {
        $this->merchantRelationRequestEntityManager = $merchantRelationRequestEntityManager;
        $this->merchantRelationRequestReader = $merchantRelationRequestReader;
        $this->merchantRelationRequestConfig = $merchantRelationRequestConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        return $merchantRelationRequestTransfer->getStatusOrFail() === SharedMerchantRelationRequestConfig::STATUS_REJECTED;
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

        foreach ($this->merchantRelationRequestConfig->getModifiableFieldsAllowedForRejection() as $allowedField) {
            $persistedMerchantRelationRequest->offsetSet($allowedField, $merchantRelationRequestTransfer->offsetGet($allowedField));
        }

        return $this->merchantRelationRequestEntityManager
            ->updateMerchantRelationRequest($persistedMerchantRelationRequest);
    }
}

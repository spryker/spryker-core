<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig as SharedMerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;

class IsRejectableRequestValidatorRule extends AbstractStatusApplicableRequestValidatorRule
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_CANT_BE_REJECTED = 'merchant_relation_request.validation.cant_be_rejected';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_NOT_FOUND = 'merchant_relation_request.validation.not_found';

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig
     */
    protected MerchantRelationRequestConfig $merchantRelationRequestConfig;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface
     */
    protected MerchantRelationRequestReaderInterface $merchantRelationRequestReader;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig $merchantRelationRequestConfig
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface $merchantRelationRequestReader
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        MerchantRelationRequestConfig $merchantRelationRequestConfig,
        MerchantRelationRequestReaderInterface $merchantRelationRequestReader
    ) {
        $this->errorAdder = $errorAdder;
        $this->merchantRelationRequestConfig = $merchantRelationRequestConfig;
        $this->merchantRelationRequestReader = $merchantRelationRequestReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    protected function isApplicable(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        $newStatus = $merchantRelationRequestTransfer->getStatusOrFail();

        return $newStatus === SharedMerchantRelationRequestConfig::STATUS_REJECTED;
    }

    /**
     * @param string|int $entityIdentifier
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param array<\Generated\Shared\Transfer\MerchantRelationRequestTransfer> $existingMerchantRelationRequests
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return void
     */
    protected function validateRequest(
        int|string $entityIdentifier,
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        array $existingMerchantRelationRequests,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): void {
        $persistedMerchantRelationRequest = $existingMerchantRelationRequests[$merchantRelationRequestTransfer->getUuidOrFail()] ?? null;

        if (!$persistedMerchantRelationRequest) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_REQUEST_NOT_FOUND,
            );

            return;
        }

        if (!$this->isRequestRejectable($persistedMerchantRelationRequest)) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_REQUEST_CANT_BE_REJECTED,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    protected function isRequestRejectable(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        return in_array(
            $merchantRelationRequestTransfer->getStatusOrFail(),
            $this->merchantRelationRequestConfig->getRejectableRequestStatuses(),
            true,
        );
    }
}

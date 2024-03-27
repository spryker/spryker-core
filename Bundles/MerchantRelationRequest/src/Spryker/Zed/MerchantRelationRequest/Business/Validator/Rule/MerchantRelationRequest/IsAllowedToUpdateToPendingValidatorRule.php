<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig as SharedMerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;

class IsAllowedToUpdateToPendingValidatorRule extends AbstractStatusApplicableRequestValidatorRule
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_CANT_BECOME_PENDING = 'merchant_relation_request.validation.cant_become_pending';

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
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface
     */
    protected MerchantRelationValidatorRuleInterface $companyAccountCompatibilityValidatorRule;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig $merchantRelationRequestConfig
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface $merchantRelationRequestReader
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\MerchantRelationValidatorRuleInterface $companyAccountCompatibilityValidatorRule
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        MerchantRelationRequestConfig $merchantRelationRequestConfig,
        MerchantRelationRequestReaderInterface $merchantRelationRequestReader,
        MerchantRelationValidatorRuleInterface $companyAccountCompatibilityValidatorRule
    ) {
        $this->errorAdder = $errorAdder;
        $this->merchantRelationRequestConfig = $merchantRelationRequestConfig;
        $this->merchantRelationRequestReader = $merchantRelationRequestReader;
        $this->companyAccountCompatibilityValidatorRule = $companyAccountCompatibilityValidatorRule;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    protected function isApplicable(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        $newStatus = $merchantRelationRequestTransfer->getStatusOrFail();

        return $newStatus === SharedMerchantRelationRequestConfig::STATUS_PENDING;
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

        if (!$this->isRequestCanBecomePending($persistedMerchantRelationRequest)) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_REQUEST_CANT_BECOME_PENDING,
            );

            return;
        }

        $companyAccountCompatibilityErrorCollectionTransfer = $this->companyAccountCompatibilityValidatorRule
            ->validate(new ArrayObject([$merchantRelationRequestTransfer]));

        if ($companyAccountCompatibilityErrorCollectionTransfer->getErrors()->count()) {
            foreach ($companyAccountCompatibilityErrorCollectionTransfer->getErrors() as $errorTransfer) {
                $errorCollectionTransfer->addError($errorTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    protected function isRequestCanBecomePending(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        return in_array(
            $merchantRelationRequestTransfer->getStatusOrFail(),
            $this->merchantRelationRequestConfig->getPendingUpdateRequestStatuses(),
            true,
        );
    }
}

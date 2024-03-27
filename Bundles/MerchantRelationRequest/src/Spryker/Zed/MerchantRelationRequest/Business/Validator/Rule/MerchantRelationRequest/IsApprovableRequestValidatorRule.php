<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig as SharedMerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;

class IsApprovableRequestValidatorRule extends AbstractStatusApplicableRequestValidatorRule
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED = 'merchant_relation_request.validation.cant_be_approved';

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
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface
     */
    protected AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig $merchantRelationRequestConfig
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Reader\MerchantRelationRequestReaderInterface $merchantRelationRequestReader
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        MerchantRelationRequestConfig $merchantRelationRequestConfig,
        MerchantRelationRequestReaderInterface $merchantRelationRequestReader,
        AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor
    ) {
        $this->errorAdder = $errorAdder;
        $this->merchantRelationRequestConfig = $merchantRelationRequestConfig;
        $this->merchantRelationRequestReader = $merchantRelationRequestReader;
        $this->assigneeCompanyBusinessUnitExtractor = $assigneeCompanyBusinessUnitExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    protected function isApplicable(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): bool
    {
        $newStatus = $merchantRelationRequestTransfer->getStatusOrFail();

        return $newStatus === SharedMerchantRelationRequestConfig::STATUS_APPROVED;
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

        if (!$this->isRequestApprovable($persistedMerchantRelationRequest, $merchantRelationRequestTransfer)) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED,
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $persistedMerchantRelationRequest
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return bool
     */
    protected function isRequestApprovable(
        MerchantRelationRequestTransfer $persistedMerchantRelationRequest,
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): bool {
        $isRequestApprovable = in_array(
            $persistedMerchantRelationRequest->getStatusOrFail(),
            $this->merchantRelationRequestConfig->getApprovableRequestStatuses(),
            true,
        );

        if (!$isRequestApprovable) {
            return false;
        }

        if (!$merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits()->count()) {
            return false;
        }

        return !$this->hasNewAssigneeBusinessUnits($merchantRelationRequestTransfer, $persistedMerchantRelationRequest);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $persistedMerchantRelationRequest
     *
     * @return bool
     */
    protected function hasNewAssigneeBusinessUnits(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        MerchantRelationRequestTransfer $persistedMerchantRelationRequest
    ): bool {
        return (bool)array_diff(
            $this->assigneeCompanyBusinessUnitExtractor->extractCompanyBusinessUnitIds($merchantRelationRequestTransfer),
            $this->assigneeCompanyBusinessUnitExtractor->extractCompanyBusinessUnitIds($persistedMerchantRelationRequest),
        );
    }
}

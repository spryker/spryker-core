<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Checker;

use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Service\CustomersRestApiToUtilTextServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckoutBillingAddressChecker implements CheckoutBillingAddressCheckerInterface
{
    /**
     * @uses \Spryker\Glue\RestRequestValidator\RestRequestValidatorConfig::RESPONSE_CODE_REQUEST_INVALID
     *
     * @var string
     */
    protected const RESPONSE_CODE_REQUEST_INVALID = '901';

    /**
     * @var array<string, string>
     */
    protected const MANDATORY_BILLING_ADDRESS_FIELDS_MAP = [
        'salutation' => RestAddressTransfer::SALUTATION,
        'first_name' => RestAddressTransfer::FIRST_NAME,
        'last_name' => RestAddressTransfer::LAST_NAME,
        'address1' => RestAddressTransfer::ADDRESS1,
        'address2' => RestAddressTransfer::ADDRESS2,
        'zip_code' => RestAddressTransfer::ZIP_CODE,
        'city' => RestAddressTransfer::CITY,
        'iso2_code' => RestAddressTransfer::ISO2_CODE,
    ];

    /**
     * @var \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig
     */
    protected CustomersRestApiConfig $customersRestApiConfig;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Service\CustomersRestApiToUtilTextServiceInterface
     */
    protected CustomersRestApiToUtilTextServiceInterface $utilTextService;

    /**
     * @param \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig $customersRestApiConfig
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Service\CustomersRestApiToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        CustomersRestApiConfig $customersRestApiConfig,
        CustomersRestApiToUtilTextServiceInterface $utilTextService
    ) {
        $this->customersRestApiConfig = $customersRestApiConfig;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function checkMandatoryFields(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restAddressTransfer = $restCheckoutRequestAttributesTransfer->getBillingAddress();

        if (!$restAddressTransfer || $this->hasSkippingValidationFields($restAddressTransfer->toArray())) {
            return new RestErrorCollectionTransfer();
        }

        return $this->checkMandatoryFieldsExistence($restAddressTransfer->toArray());
    }

    /**
     * @param array<string, mixed> $billingAddressData
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function checkMandatoryFieldsExistence(array $billingAddressData): RestErrorCollectionTransfer
    {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        foreach (static::MANDATORY_BILLING_ADDRESS_FIELDS_MAP as $mandatoryBillingAddressField => $restAddressField) {
            $billingAddressValue = $billingAddressData[$mandatoryBillingAddressField] ?? null;
            if ($billingAddressValue !== null) {
                continue;
            }

            $restErrorCollectionTransfer->addRestError($this->buildErrorMessage($restAddressField));
        }

        return $restErrorCollectionTransfer;
    }

    /**
     * @param array<string, mixed> $billingAddressData
     *
     * @return bool
     */
    protected function hasSkippingValidationFields(array $billingAddressData): bool
    {
        $fieldsToSkipValidation = $this->customersRestApiConfig->getBillingAddressFieldsToSkipValidation();
        if ($fieldsToSkipValidation === []) {
            return false;
        }

        $fieldsToSkipValidation = array_map(function (string $field) {
            return $this->utilTextService->camelCaseToSeparator($field, '_');
        }, $fieldsToSkipValidation);

        foreach ($billingAddressData as $key => $value) {
            if (in_array($key, $fieldsToSkipValidation, true) && $value !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $restAddressField
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function buildErrorMessage(string $restAddressField): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(static::RESPONSE_CODE_REQUEST_INVALID)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(sprintf('billingAddress.%s => This field is missing.', $restAddressField));
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Validator;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiValidationErrorTransfer;
use Spryker\Zed\MerchantRelationshipApi\Business\Request\MerchantRelationshipRequestDataInterface;
use Symfony\Component\HttpFoundation\Request;

class MerchantRelationshipApiValidator implements MerchantRelationshipApiValidatorInterface
{
    /**
     * @var string
     */
    protected const KEY_CAN_BE_EMPTY = 'canBeEmpty';

    /**
     * @var string
     */
    protected const KEY_REQUIRED_FIELDS = 'requiredFields';

    /**
     * @var array<string>
     */
    protected const POST_REQUIRED_FIELDS = [
        MerchantRelationshipRequestDataInterface::KEY_MERCHANT_REFERENCE,
        MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY,
        MerchantRelationshipRequestDataInterface::KEY_ID_BUSINESS_UNIT_OWNER,
    ];

    /**
     * @var array<string>
     */
    protected const PATCH_FORBIDDEN_FIELDS = [
        MerchantRelationshipRequestDataInterface::KEY_MERCHANT_REFERENCE,
        MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY,
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected const REQUIRED_NESTED_FIELDS = [
        MerchantRelationshipRequestDataInterface::KEY_ASSIGNED_BUSINESS_UNITS => [
            self::KEY_CAN_BE_EMPTY => true,
            self::KEY_REQUIRED_FIELDS => [
                MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY_BUSINESS_UNIT,
            ],
        ],
        MerchantRelationshipRequestDataInterface::KEY_ASSIGNED_PRODUCT_LISTS => [
            self::KEY_CAN_BE_EMPTY => true,
            self::KEY_REQUIRED_FIELDS => [
                MerchantRelationshipRequestDataInterface::KEY_ID_PRODUCT_LIST,
            ],
        ],
    ];

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validateMerchantRelationshipRequestData(ApiRequestTransfer $apiRequestTransfer): array
    {
        $apiDataTransfer = $apiRequestTransfer->getApiData();
        $data = $apiDataTransfer ? $apiDataTransfer->getData() : [];

        if ($apiRequestTransfer->getRequestType() === Request::METHOD_POST) {
            return $this->validatePostRequestData($data);
        }

        return $this->validatePatchRequestData($data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    protected function validatePostRequestData(array $data): array
    {
        $apiValidationErrorTransfers = [];
        foreach (static::POST_REQUIRED_FIELDS as $requiredField) {
            $apiValidationErrorTransfers = $this->validateRequiredField($data, $requiredField, $apiValidationErrorTransfers);
        }

        foreach (static::REQUIRED_NESTED_FIELDS as $nestedField => $requiredFieldData) {
            $apiValidationErrorTransfers = $this->validateNestedRequiredField(
                $data,
                $nestedField,
                $requiredFieldData[static::KEY_REQUIRED_FIELDS],
                $apiValidationErrorTransfers,
                $requiredFieldData[static::KEY_CAN_BE_EMPTY],
            );
        }

        return $apiValidationErrorTransfers;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    protected function validatePatchRequestData(array $data): array
    {
        $apiValidationErrorTransfers = [];
        foreach (static::PATCH_FORBIDDEN_FIELDS as $forbiddenField) {
            $apiValidationErrorTransfers = $this->validateForbiddenField($data, $forbiddenField, $apiValidationErrorTransfers);
        }

        foreach (static::REQUIRED_NESTED_FIELDS as $nestedField => $requiredFieldData) {
            $apiValidationErrorTransfers = $this->validateNestedRequiredField(
                $data,
                $nestedField,
                $requiredFieldData[static::KEY_REQUIRED_FIELDS],
                $apiValidationErrorTransfers,
                $requiredFieldData[static::KEY_CAN_BE_EMPTY],
            );
        }

        return $apiValidationErrorTransfers;
    }

    /**
     * @param array<string, mixed> $data
     * @param string $field
     * @param array<\Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    protected function validateRequiredField(array $data, string $field, array $apiValidationErrorTransfers): array
    {
        if (empty($data[$field])) {
            $apiValidationErrorTransfers[] = $this->createApiValidationErrorTransfer($field, sprintf('Missing value for required field "%s"', $field));
        }

        return $apiValidationErrorTransfers;
    }

    /**
     * @param array<string, mixed> $data
     * @param string $field
     * @param array<\Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    protected function validateForbiddenField(array $data, string $field, array $apiValidationErrorTransfers): array
    {
        if (isset($data[$field])) {
            $apiValidationErrorTransfers[] = $this->createApiValidationErrorTransfer($field, sprintf('"%s" cannot be changed', $field));
        }

        return $apiValidationErrorTransfers;
    }

    /**
     * @param array<string, mixed> $data
     * @param string $nestedField
     * @param array<string> $requiredFields
     * @param array<\Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     * @param bool $canBeEmpty
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    protected function validateNestedRequiredField(
        array $data,
        string $nestedField,
        array $requiredFields,
        array $apiValidationErrorTransfers,
        bool $canBeEmpty = true
    ): array {
        if ($canBeEmpty && empty($data[$nestedField])) {
            return $apiValidationErrorTransfers;
        }

        if (!$canBeEmpty && empty($data[$nestedField])) {
            $apiValidationErrorTransfers[] = $this->createApiValidationErrorTransfer($nestedField, sprintf('Required field "%s" cannot be empty', $nestedField));

            return $apiValidationErrorTransfers;
        }

        foreach ($data[$nestedField] as $key => $nestedFieldData) {
            $apiValidationErrorTransfers = $this->validateNestedFieldData($requiredFields, $nestedFieldData, $nestedField, $key, $apiValidationErrorTransfers);
        }

        return $apiValidationErrorTransfers;
    }

    /**
     * @param array<string> $requiredFields
     * @param array<string, mixed> $nestedFieldData
     * @param string $nestedField
     * @param string $key
     * @param array<\Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    protected function validateNestedFieldData(
        array $requiredFields,
        array $nestedFieldData,
        string $nestedField,
        string $key,
        array $apiValidationErrorTransfers
    ): array {
        foreach ($requiredFields as $requiredField) {
            if (empty($nestedFieldData[$requiredField])) {
                $apiValidationErrorTransfers[] = $this->createApiValidationErrorTransfer(
                    sprintf('{%s}[{%s}].{%s}', $nestedField, $key, $requiredField),
                    sprintf('Missing value for required field "%s" in one of "%s"', $requiredField, $nestedField),
                );
            }
        }

        return $apiValidationErrorTransfers;
    }

    /**
     * @param string $field
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer
     */
    protected function createApiValidationErrorTransfer(string $field, string $message): ApiValidationErrorTransfer
    {
        return (new ApiValidationErrorTransfer())
            ->setField($field)
            ->addMessages($message);
    }
}

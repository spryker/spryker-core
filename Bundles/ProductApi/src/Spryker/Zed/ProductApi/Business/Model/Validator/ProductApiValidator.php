<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiValidationErrorTransfer;
use Spryker\Zed\ProductApi\Business\Request\ProductRequestDataInterface;

class ProductApiValidator implements ProductApiValidatorInterface
{
    /**
     * @var array<string>
     */
    protected const REQUIRED_FIELDS = [
        ProductRequestDataInterface::KEY_NAME,
        ProductRequestDataInterface::KEY_SKU,
        ProductRequestDataInterface::KEY_FK_LOCALE,
        ProductRequestDataInterface::KEY_ID_TAX_SET,
    ];

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer): array
    {
        $data = $apiRequestTransfer->getApiDataOrFail()->getData();

        $errors = [];
        foreach (static::REQUIRED_FIELDS as $field) {
            $errors = $this->assertRequiredField($data, $field, $errors);
        }

        return $errors;
    }

    /**
     * @param array<string, mixed> $data
     * @param string $field
     * @param array<\Generated\Shared\Transfer\ApiValidationErrorTransfer> $errors
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    protected function assertRequiredField(array $data, $field, array $errors)
    {
        if (!isset($data[$field]) || (array_key_exists($field, $data) && !$data[$field])) {
            $message = sprintf('Missing value for required field "%s"', $field);
            $errors[] = $this->createApiValidationErrorTransfer($field, $message);
        }

        return $errors;
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

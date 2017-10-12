<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiDataTransfer;

class ProductApiValidator implements ProductApiValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return array
     */
    public function validate(ApiDataTransfer $apiDataTransfer)
    {
        $data = $apiDataTransfer->getData();

        $errors = [];
        $errors = $this->assertRequiredField($data, 'name', $errors);
        $errors = $this->assertRequiredField($data, 'sku', $errors);
        $errors = $this->assertRequiredField($data, 'fk_locale', $errors);
        $errors = $this->assertRequiredField($data, 'id_tax_set', $errors);

        return $errors;
    }

    /**
     * @param array $data
     * @param string $field
     * @param array $errors
     *
     * @return array
     */
    protected function assertRequiredField(array $data, $field, array $errors)
    {
        if (!isset($data[$field]) || (array_key_exists($field, $data) && !$data[$field])) {
            $message = sprintf('Missing value for required field "%s"', $field);
            $errors[$field][] = $message;
        }

        return $errors;
    }
}

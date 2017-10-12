<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiDataTransfer;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;

class CustomerApiValidator implements CustomerApiValidatorInterface
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
        $errors = $this->assertRequiredField($data, 'email', $errors);
        $errors = $this->assertValidEmail($data, 'email', $errors);

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

    /**
     * @param array $data
     * @param string $field
     * @param array $errors
     *
     * @return array
     */
    protected function assertValidEmail(array $data, $field, array $errors)
    {
        if (isset($data[$field])) {
            $validator = Validation::createValidator();
            $violations = $validator->validate($data[$field], [
                new Email(),
            ]);
            /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
            foreach ($violations as $violation) {
                $errors[$field] = $violation->getMessage();
            }
        }

        return $errors;
    }
}

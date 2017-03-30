<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model\Validator;

use Generated\Shared\Transfer\ApiDataTransfer;
use Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface;

class CustomerApiValidator implements CustomerApiValidatorInterface
{

    /**
     * @var \Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface $transferMapper
     */
    public function __construct(TransferMapperInterface $transferMapper)
    {
        $this->transferMapper = $transferMapper;
    }

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
        $errors = $this->assertRequiredField($data, 'fk_locale', $errors);

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
        /*            $validationErrorTransfer = new ApiValidationErrorTransfer();
            $validationErrorTransfer->setField($field);
            $validationErrorTransfer->setMessages([$message]);*/

        if (!isset($data[$field]) || (array_key_exists($field, $data) && !$data[$field])) {
            $message = sprintf('Missing value for required field "%s"', $field);
            $errors[$field][] = $message;
        }

        return $errors;
    }

}

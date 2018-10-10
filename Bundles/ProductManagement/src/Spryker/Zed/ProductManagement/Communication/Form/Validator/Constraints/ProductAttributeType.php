<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Symfony\Component\Validator\Constraint;

class ProductAttributeType extends Constraint
{
    public const TYPE_NUMBER = 'number';

    /**
     * @var string
     */
    public $message = 'The entered value does not match the required input type. The input type is "{{ type }}"';

    /**
     * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public $productManagementAttributeTransfer;

    /**
     * @var array
     */
    public $fields;

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param array $fields
     * @param array $options
     */
    public function __construct(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        array $fields,
        array $options = []
    ) {
        parent::__construct($options);

        $this->productManagementAttributeTransfer = $productManagementAttributeTransfer;
        $this->fields = $fields;
    }
}

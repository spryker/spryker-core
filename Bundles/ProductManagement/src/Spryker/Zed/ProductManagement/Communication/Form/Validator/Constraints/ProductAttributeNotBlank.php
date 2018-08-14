<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ProductAttributeNotBlank extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Attribute value should not be empty';

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }
}

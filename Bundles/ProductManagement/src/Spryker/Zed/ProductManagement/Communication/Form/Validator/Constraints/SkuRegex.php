<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Regex;

class SkuRegex extends Regex
{
    /**
     * @param array|null $options
     */
    public function __construct($options = null)
    {
        $defaults = [
            'pattern' => '/^[a-zA-Z0-9-_\.]+$/u',
            'message' => 'Invalid value provided. Please use only alphanumeric characters and  ". - _"',
        ];

        $options = array_merge($defaults, (array)$options);

        parent::__construct($options);
    }
}

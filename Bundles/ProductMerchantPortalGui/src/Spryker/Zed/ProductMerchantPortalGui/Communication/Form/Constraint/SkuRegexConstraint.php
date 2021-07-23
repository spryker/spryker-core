<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraints\Regex;

class SkuRegexConstraint extends Regex
{
    protected const REGEX_PATTERN = '/^[a-zA-Z0-9-_\.]+$/u';
    protected const MESSAGE = 'Invalid value provided. Please use only alphanumeric characters and  . - _';

    /**
     * @param array|null $options
     */
    public function __construct($options = null)
    {
        $defaults = [
            'pattern' => static::REGEX_PATTERN,
            'message' => static::MESSAGE,
        ];

        $options = array_merge($defaults, (array)$options);

        parent::__construct($options);
    }
}

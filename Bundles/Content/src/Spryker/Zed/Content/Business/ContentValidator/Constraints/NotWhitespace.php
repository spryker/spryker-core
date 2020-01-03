<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentValidator\Constraints;

use Symfony\Component\Validator\Constraint;

class NotWhitespace extends Constraint
{
    public const IS_EMPTY_ERROR = 'is_empty_error';

    /**
     * @var array
     */
    protected static $errorNames = [
        self::IS_EMPTY_ERROR => 'IS_EMPTY_ERROR',
    ];

    /**
     * @var string
     */
    public $message = 'This value should not be empty.';
}

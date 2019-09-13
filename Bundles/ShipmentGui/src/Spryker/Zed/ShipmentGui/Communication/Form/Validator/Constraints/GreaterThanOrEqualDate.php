<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\Validator\Constraints;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class GreaterThanOrEqualDate extends GreaterThanOrEqual
{
    public const VALIDATION_VALID_DATE_FORMAT = 'Y-m-d'; // Format accepted by date().

    /**
     * Date time format acceptable by date().
     *
     * @var string
     */
    public $format = self::VALIDATION_VALID_DATE_FORMAT;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\UtilText\Helper;

use Codeception\Module;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class UtilTextHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length)
    {
        return $this->getLocator()->utilText()->service()->generateRandomString($length);
    }
}

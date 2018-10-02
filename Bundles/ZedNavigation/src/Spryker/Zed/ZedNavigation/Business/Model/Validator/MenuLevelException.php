<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Validator;

use Exception;

class MenuLevelException extends Exception
{
    public const ERROR_MESSAGE = 'The Menu is only allowed to have %s Sub-Levels per branch. More Levels found in "%s"!';

    /**
     * @param int $maxLevelCount
     * @param string $pageTitle
     */
    public function __construct($maxLevelCount, $pageTitle)
    {
        $errorMessage = sprintf(self::ERROR_MESSAGE, $maxLevelCount, $pageTitle);
        parent::__construct($errorMessage);
    }
}

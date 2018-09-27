<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Validator;

use Exception;

class UrlUniqueException extends Exception
{
    public const ERROR_MESSAGE = 'The URL "%s" is already used in the Menu!';

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $errorMessage = sprintf(self::ERROR_MESSAGE, $url);
        parent::__construct($errorMessage);
    }
}

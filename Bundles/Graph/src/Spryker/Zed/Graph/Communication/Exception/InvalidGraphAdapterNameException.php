<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Graph\Communication\Exception;

use Exception;

class InvalidGraphAdapterNameException extends AbstractGraphAdapterException
{
    /**
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = '', $code = 0, ?Exception $previous = null)
    {
        $message .= PHP_EOL . self::MESSAGE;

        parent::__construct($message, $code, $previous);
    }
}

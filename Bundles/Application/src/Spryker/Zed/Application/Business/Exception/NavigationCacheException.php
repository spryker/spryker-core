<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Exception;

class NavigationCacheException extends \Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        $message .= PHP_EOL . ''
        parent::__construct($message, $code, $previous);
    }

}

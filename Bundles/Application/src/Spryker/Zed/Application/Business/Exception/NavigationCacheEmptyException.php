<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Business\Exception;

class NavigationCacheEmptyException extends AbstractNavigationCacheException
{

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous = null
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        $message .= PHP_EOL . PHP_EOL . self::MESSAGE;

        parent::__construct($message, $code, $previous);
    }

}

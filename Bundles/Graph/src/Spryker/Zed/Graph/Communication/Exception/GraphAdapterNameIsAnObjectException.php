<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Graph\Communication\Exception;

class GraphAdapterNameIsAnObjectException extends AbstractGraphAdapterException
{

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $message = 'Your config returned an object instance, this is not allowed.'
            . PHP_EOL
            . self::MESSAGE;

        parent::__construct($message, $code, $previous);
    }

}

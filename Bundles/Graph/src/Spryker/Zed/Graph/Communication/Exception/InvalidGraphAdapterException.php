<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Graph\Communication\Exception;

use Spryker\Shared\Graph\GraphAdapterInterface;

class InvalidGraphAdapterException extends AbstractGraphAdapterException
{

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Provided "%s" must be an instanceof "%s"', $message, GraphAdapterInterface::class)
            . PHP_EOL
            . self::MESSAGE;

        parent::__construct($message, $code, $previous);
    }

}

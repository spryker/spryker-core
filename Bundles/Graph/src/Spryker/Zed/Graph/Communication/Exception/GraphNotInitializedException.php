<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Graph\Communication\Exception;

class GraphNotInitializedException extends \Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = '', $code = 0 , \Exception $previous = null)
    {
        $message .= 'Graph not initialized. Please call GraphPlugin::init()';

        parent::__construct($message, $code, $previous);
    }


}

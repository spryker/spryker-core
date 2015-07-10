<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Validator;

class UrlUniqueException extends \Exception
{

    const ERROR_MESSAGE = 'The Url "%s" is already used in the Menu!';

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $errorMessage = sprintf(self::ERROR_MESSAGE, $url);
        parent::__construct($errorMessage);
    }

}

<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service\Exception;

class TranslationNotFoundException extends \RuntimeException
{

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        parent::__construct('The translation key was not found:' . PHP_EOL . '[key] ' . $key);
    }
}

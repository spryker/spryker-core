<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library;

class Translate extends \Zend_Translate
{
}

/**
 * @param $string
 *
 * @throws \ErrorException
 *
 * @return mixed
 */
function __($string)
{
    $registry = \Zend_Registry::getInstance();
    if (!$registry->isRegistered('Zend_Translate')) {
        throw new \ErrorException('No instance of Zend_Translate initiated.');
    }

    /* @var Translate $instance */
    $instance = $registry->get('Zend_Translate');

    return $instance->_($string);
}

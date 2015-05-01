<?php

class SprykerFeature_Zed_Library_Translate extends Zend_Translate
{

}

/**
 * @param $string
 * @return mixed
 * @throws ErrorException
 */
function __($string)
{
    $registry = Zend_Registry::getInstance();
    /* @var $instance \SprykerFeature_Zed_Library_Translate */
    if ($registry->isRegistered('Zend_Translate')) {
        $instance = $registry->get('Zend_Translate');
        return $instance->_($string);
    } else {
        throw new ErrorException('No instance of Zend_Translate initiated.');
    }
}

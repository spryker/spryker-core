<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class TransferTwigExtensions extends \Twig_Extension
{
    public function getName()
    {
        return 'TransferTwigExtensions';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('writeIfNot', function($valueToWrite, $notToBe, $valueToCheck=null){

                if ( empty($valueToCheck) ) {
                    $valueToCheck = $valueToWrite;
                }

                if ( ! preg_match('/'.$notToBe.'/', $valueToCheck) ) {
                    return $valueToWrite;
                }

                return null;
            }),
            new \Twig_SimpleFunction('writeIf', function($valueToWrite, $toBe, $valueToCheck=null){

                if ( empty($valueToCheck) ) {
                    $valueToCheck = $valueToWrite;
                }

                if ( $valueToCheck === $toBe ) {
                    return $valueToWrite;
                }

                return null;
            }),
        ];
    }
}

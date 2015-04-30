<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class TransferTwigExtensions extends \Twig_Extension
{
    public function getName()
    {
        return 'TransferTwigExtensions';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('changeArrayType', function($text){
                return strtr($text, [
                    '[]' => '',
                ]);
            }),
            new \Twig_SimpleFilter('singular', function($text){
                // just cat the ending "s" from the word
                return preg_replace('/(s$){1}/', '', $text);
            }),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('writeIfNot', function($valueToWrite, $notToBe, $valueToCheck=null){

                if ( empty($valueToCheck) && ! is_null($valueToCheck) ) {
                    $valueToCheck = $valueToWrite;
                }

                if ( ! is_null($valueToCheck) && ! preg_match('/'.$notToBe.'/', $valueToCheck) ) {
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

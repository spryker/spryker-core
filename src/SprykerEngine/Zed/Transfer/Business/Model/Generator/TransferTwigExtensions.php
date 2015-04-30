<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

use Zend\Filter\Word\UnderscoreToCamelCase;

class TransferTwigExtensions extends \Twig_Extension
{
    public function getName()
    {
        return 'TransferTwigExtensions';
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('changeArrayType', function ($text) {
                return strtr($text, [
                    '[]' => '',
                ]);
            }),
            new \Twig_SimpleFilter('singular', function ($text) {
                // just cut the ending "s" from the word
                return preg_replace('/(s$){1}/', '', $text);
            }),
            new \Twig_SimpleFilter('quotedIfNotIntegers', function ($text) {
                return (preg_match('/(\[\]|true|false|null|[0-9]+)/', $text)) ? $text : sprintf("'%s'", $text);
            }, [
                'is_safe' => ['html'],
            ]),
            new \Twig_SimpleFilter('camelCase', function ($string) {
                $filter = new UnderscoreToCamelCase();
                $string = $filter->filter($string);

                return ucwords($string);
            }),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('writeIfNot', function ($valueToWrite, $notToBe, $valueToCheck = null) {

                if (empty($valueToCheck) && !is_null($valueToCheck)) {
                    $valueToCheck = $valueToWrite;
                }

                if (!is_null($valueToCheck) && !preg_match('/' . $notToBe . '/', $valueToCheck)) {
                    return $valueToWrite;
                }

                return null;
            }),
            new \Twig_SimpleFunction('writeIf', function ($valueToWrite, $toBe, $valueToCheck = null) {

                if (empty($valueToCheck)) {
                    $valueToCheck = $valueToWrite;
                }

                if ($valueToCheck === $toBe) {
                    return $valueToWrite;
                }

                return null;
            }),
        ];
    }
}

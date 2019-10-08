<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilSanitize;

use ArrayObject;
use Codeception\Actor;
use stdClass;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class UtilSanitizeServiceTester extends Actor
{
    use _generated\UtilSanitizeServiceTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return array
     */
    public function getArrayToFilter(): array
    {
        return [
            'emptyArray' => [],
            'false' => false,
            'true' => true,
            'zero' => 0,
            'stringZero' => '0',
            'emptyString' => '',
            'someObject' => new stdClass(),
            'emptyCountable' => new ArrayObject(),
            'countable' => new ArrayObject(['test']),
            'nested' => [
                'foo' => [
                    'bar' => [
                        'emptyString' => '',
                        'null' => null,
                        'string' => 'String',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function getArrayFilterRecursiveExpectedArray(array $array): array
    {
        return [
            'true' => $array['true'],
            'stringZero' => '0',
            'someObject' => $array['someObject'],
            'countable' => $array['countable'],
            'nested' => [
                'foo' => [
                    'bar' => [
                        'string' => 'String',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function getFilterOutBlankValuesRecursivelyExpectedArray(array $array): array
    {
        return [
            'false' => $array['false'],
            'true' => $array['true'],
            'zero' => $array['zero'],
            'stringZero' => '0',
            'someObject' => $array['someObject'],
            'countable' => $array['countable'],
            'nested' => [
                'foo' => [
                    'bar' => [
                        'string' => 'String',
                    ],
                ],
            ],
        ];
    }
}

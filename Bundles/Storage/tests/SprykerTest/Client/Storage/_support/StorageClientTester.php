<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage;

use Codeception\Actor;
use ReflectionClass;

/**
 * Inherited Methods
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
class StorageClientTester extends Actor
{
    use _generated\StorageClientTesterActions;

    /**
     * @param object|string $object
     * @param string $propertyName
     * @param mixed $value
     *
     * @return void
     */
    public function setProtectedProperty($object, $propertyName, $value)
    {
        $storageClientReflection = new ReflectionClass($object);
        $bufferedValuesReflection = $storageClientReflection->getProperty($propertyName);
        $bufferedValuesReflection->setAccessible(true);
        $bufferedValuesReflection->setValue($value);
    }
}

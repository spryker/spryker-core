<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business\Locator;

use SprykerFeature\Zed\ProductSearch\Business\Operation\OperationInterface;

/**
 * Class OperationLocator
 */
class OperationLocator implements OperationLocatorInterface
{

    protected $registeredOperations = [];

    /**
     * @param OperationInterface $operation
     */
    public function addOperation(OperationInterface $operation)
    {
        $this->registeredOperations[$operation->getName()] = $operation;
    }

    /**
     * @param string $name
     *
     * @return null|OperationInterface
     */
    public function findOperationByName($name = 'CopyToField')
    {
        if (array_key_exists($name, $this->registeredOperations)) {
            return $this->registeredOperations[$name];
        }

        return;
    }

}

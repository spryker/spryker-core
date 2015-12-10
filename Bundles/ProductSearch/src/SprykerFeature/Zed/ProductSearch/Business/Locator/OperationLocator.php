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

    const COPY_TO_FIELD = 'CopyToField';

    protected $registeredOperations = [];

    /**
     * @param OperationInterface $operation
     *
     * @return void
     */
    public function addOperation(OperationInterface $operation)
    {
        $this->registeredOperations[$operation->getName()] = $operation;
    }

    /**
     * @param string $name
     *
     * @return OperationInterface|null
     */
    public function findOperationByName($name = self::COPY_TO_FIELD)
    {
        if (array_key_exists($name, $this->registeredOperations)) {
            return $this->registeredOperations[$name];
        }

        return null;
    }

}

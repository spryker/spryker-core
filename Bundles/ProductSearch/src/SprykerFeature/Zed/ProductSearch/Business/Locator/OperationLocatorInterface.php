<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Business\Locator;

use SprykerFeature\Zed\ProductSearch\Business\Operation\OperationInterface;

/**
 * Class OperationLocator
 */
interface OperationLocatorInterface
{

    /**
     * @param OperationInterface $operation
     */
    public function addOperation(OperationInterface $operation);

    /**
     * @param string $name
     *
     * @return OperationInterface
     */
    public function findOperationByName($name = 'CopyToField');

}

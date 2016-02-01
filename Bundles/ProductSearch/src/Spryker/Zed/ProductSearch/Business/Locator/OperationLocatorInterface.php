<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Business\Locator;

use Spryker\Zed\ProductSearch\Business\Operation\OperationInterface;

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
     * @return \Spryker\Zed\ProductSearch\Business\Operation\OperationInterface
     */
    public function findOperationByName($name = 'CopyToField');

}

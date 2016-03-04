<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Locator;

use Spryker\Zed\ProductSearch\Business\Operation\OperationInterface;

/**
 * Class OperationLocator
 */
interface OperationLocatorInterface
{

    /**
     * @param \Spryker\Zed\ProductSearch\Business\Operation\OperationInterface $operation
     *
     * @return void
     */
    public function addOperation(OperationInterface $operation);

    /**
     * @param string $name
     *
     * @return \Spryker\Zed\ProductSearch\Business\Operation\OperationInterface
     */
    public function findOperationByName($name = 'CopyToField');

}

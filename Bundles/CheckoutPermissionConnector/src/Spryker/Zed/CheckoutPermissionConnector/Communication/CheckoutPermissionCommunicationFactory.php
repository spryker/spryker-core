<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutPermissionConnector\Communication;

use Spryker\Zed\CheckoutPermissionConnector\CheckoutPermissionConnectorDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CheckoutPermissionCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CheckoutPermissionConnector\Dependency\CheckoutPermissionConnectorToFacadeInterface
     */
    public function getPermissionFacade()
    {
        return $this->getProvidedDependency(CheckoutPermissionConnectorDependencyProvider::FACADE_PERMISSION);
    }
}

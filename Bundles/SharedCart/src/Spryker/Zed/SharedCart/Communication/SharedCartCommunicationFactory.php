<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SharedCart\SharedCartDependencyProvider;

/**
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 */
class SharedCartCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToCustomerFacadeInterface
     */
    public function getCustomerFacade()
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::FACADE_CUSTOMER);
    }
}

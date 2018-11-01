<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface;
use Spryker\Zed\MultiCart\MultiCartDependencyProvider;

/**
 * @method \Spryker\Zed\MultiCart\Business\MultiCartFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiCart\MultiCartConfig getConfig()
 */
class MultiCartCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface
     */
    public function getMessengerFacade(): MultiCartToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(MultiCartDependencyProvider::FACADE_MESSENGER);
    }
}

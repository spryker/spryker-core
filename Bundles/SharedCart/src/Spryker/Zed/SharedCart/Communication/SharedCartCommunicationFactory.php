<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToMessengerFacadeInterface;
use Spryker\Zed\SharedCart\SharedCartDependencyProvider;

/**
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface getRepository()
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 */
class SharedCartCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToMessengerFacadeInterface
     */
    public function getMessengerFacade(): SharedCartToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(SharedCartDependencyProvider::FACADE_MESSENGER);
    }
}

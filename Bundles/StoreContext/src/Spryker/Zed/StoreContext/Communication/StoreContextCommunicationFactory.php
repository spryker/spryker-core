<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StoreContext\Communication\Mapper\StoreContextMapper;
use Spryker\Zed\StoreContext\Communication\Mapper\StoreContextMapperInterface;

/**
 * @method \Spryker\Zed\StoreContext\StoreContextConfig getConfig()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextRepositoryInterface getRepository()
 */
class StoreContextCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StoreContext\Communication\Mapper\StoreContextMapperInterface
     */
    public function createStoreContextMapper(): StoreContextMapperInterface
    {
        return new StoreContextMapper();
    }
}

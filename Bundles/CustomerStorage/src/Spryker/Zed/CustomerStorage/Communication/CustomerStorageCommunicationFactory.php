<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CustomerStorage\CustomerStorageConfig getConfig()
 * @method \Spryker\Zed\CustomerStorage\Business\CustomerStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface getRepository()
 */
class CustomerStorageCommunicationFactory extends AbstractCommunicationFactory
{
}

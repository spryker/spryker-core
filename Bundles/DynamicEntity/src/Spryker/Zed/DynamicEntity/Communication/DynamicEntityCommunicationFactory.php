<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\DynamicEntity\DynamicEntityConfig getConfig()
 * @method \Spryker\Zed\DynamicEntity\Business\DynamicEntityFacadeInterface getFacade()
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface getRepository()
 */
class DynamicEntityCommunicationFactory extends AbstractCommunicationFactory
{
}

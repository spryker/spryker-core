<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacadeInterface getFacade()
 */
class SalesConfigurableBundleCommunicationFactory extends AbstractCommunicationFactory
{
}

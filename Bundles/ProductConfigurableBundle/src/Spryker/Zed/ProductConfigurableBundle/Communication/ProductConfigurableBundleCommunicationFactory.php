<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurableBundle\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ProductConfigurableBundle\ProductConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\ProductConfigurableBundle\Persistence\ProductConfigurableBundleEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductConfigurableBundle\Persistence\ProductConfigurableBundleRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductConfigurableBundle\Business\ProductConfigurableBundleFacadeInterface getFacade()
 */
class ProductConfigurableBundleCommunicationFactory extends AbstractCommunicationFactory
{
}

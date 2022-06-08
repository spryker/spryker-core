<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Asset\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Asset\AssetConfig getConfig()
 * @method \Spryker\Zed\Asset\Business\AssetFacadeInterface getFacade()
 * @method \Spryker\Zed\Asset\Persistence\AssetRepositoryInterface getRepository()
 * @method \Spryker\Zed\Asset\Persistence\AssetEntityManagerInterface getEntityManager()
 */
class AssetCommunicationFactory extends AbstractCommunicationFactory
{
}

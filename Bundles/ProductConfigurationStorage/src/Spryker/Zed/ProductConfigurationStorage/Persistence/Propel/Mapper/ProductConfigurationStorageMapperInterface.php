<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConfigurationStorageTransfer;
use Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage;

interface ProductConfigurationStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage $configurationStorageEntity
     * @param \Generated\Shared\Transfer\ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
     *
     * @return \Orm\Zed\ProductConfigurationStorage\Persistence\SpyProductConfigurationStorage
     */
    public function mapSpyProductConfigurationStorageEntity(
        SpyProductConfigurationStorage $configurationStorageEntity,
        ProductConfigurationStorageTransfer $productConfigurationStorageTransfer
    ): SpyProductConfigurationStorage;
}

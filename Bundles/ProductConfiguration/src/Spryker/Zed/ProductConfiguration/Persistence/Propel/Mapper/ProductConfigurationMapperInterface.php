<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface ProductConfigurationMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productConfigurationEntitiesCollection
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    public function mapEntityCollectionToTransferCollection(
        ObjectCollection $productConfigurationEntitiesCollection
    ): ProductConfigurationCollectionTransfer;
}

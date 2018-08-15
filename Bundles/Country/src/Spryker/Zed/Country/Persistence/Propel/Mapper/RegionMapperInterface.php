<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\RegionCollectionTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface RegionMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $regionEntityCollection
     *
     * @return \Generated\Shared\Transfer\RegionCollectionTransfer
     */
    public function mapTransferCollection(ObjectCollection $regionEntityCollection): RegionCollectionTransfer;
}

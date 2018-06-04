<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence\Mapper;

use Orm\Zed\Dataset\Persistence\SpyDataset;

interface DatasetMapperInterface
{
    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getResponseDatasetTransfer(SpyDataset $datasetEntity);
}

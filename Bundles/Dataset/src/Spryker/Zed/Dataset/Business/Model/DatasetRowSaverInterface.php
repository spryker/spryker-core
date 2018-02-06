<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;

interface DatasetRowSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyDatasetRowEntityTransfer $datasetRowEntityTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function findOrCreate(SpyDatasetRowEntityTransfer $datasetRowEntityTransfer);
}

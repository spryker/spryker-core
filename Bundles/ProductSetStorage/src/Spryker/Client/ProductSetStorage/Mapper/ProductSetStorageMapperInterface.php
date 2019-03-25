<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetStorage\Mapper;

interface ProductSetStorageMapperInterface
{
    /**
     * @param array $productSetStorageStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function mapDataToTransfer(array $productSetStorageStorageData);
}

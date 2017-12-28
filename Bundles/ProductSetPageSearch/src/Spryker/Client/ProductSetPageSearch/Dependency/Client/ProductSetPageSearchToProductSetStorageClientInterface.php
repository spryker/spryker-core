<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetPageSearch\Dependency\Client;

interface ProductSetPageSearchToProductSetStorageClientInterface
{

    /**
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    public function mapProductSetStorageDataToTransfer(array $productSetStorageData);
}

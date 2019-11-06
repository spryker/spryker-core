<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage\Storage;

interface ProductAbstractBulkStorageReaderInterface
{
    /**
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function getProductAbstractStorageData(array $identifiers, string $localeName): array;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductGroup\Storage;

interface ProductStorageReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName);
}

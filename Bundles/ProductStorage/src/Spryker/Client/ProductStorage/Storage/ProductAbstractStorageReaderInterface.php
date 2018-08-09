<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Storage;

interface ProductAbstractStorageReaderInterface
{
    /**
     * @deprecated Use findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function getProductAbstractStorageData($idProductAbstract, $localeName);

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array;

    /**
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool;
}

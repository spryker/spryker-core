<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;

interface ProductStorageInterface
{

    /**
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return ProductAbstractStorageTransfer
     */
    public function getProductAbstractFromStorageById($idProductAbstract, $locale);
}

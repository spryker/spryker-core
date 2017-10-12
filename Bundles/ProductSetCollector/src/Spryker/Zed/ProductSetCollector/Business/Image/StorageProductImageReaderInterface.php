<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business\Image;

interface StorageProductImageReaderInterface
{
    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\StorageProductImageTransfer[]
     */
    public function getProductSetImageSets($idProductSet, $idLocale);
}

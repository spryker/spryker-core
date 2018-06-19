<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

interface ProductPackagingStorageWriterInterface
{
    /**
     * @param array $idProductAbstracts
     *
     * @return void
     */
    public function publish(array $idProductAbstracts): void;

    /**
     * @param int[] $idProductAbstracts
     *
     * @return void
     */
    public function unpublish(array $idProductAbstracts): void;
}

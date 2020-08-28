<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Business\Storage;

interface ProductAbstractGroupStorageWriterInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds);

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds);
}

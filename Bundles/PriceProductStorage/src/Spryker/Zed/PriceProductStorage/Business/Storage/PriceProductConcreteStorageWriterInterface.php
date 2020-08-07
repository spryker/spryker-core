<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business\Storage;

interface PriceProductConcreteStorageWriterInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publish(array $productConcreteIds);

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublish(array $productConcreteIds);
}

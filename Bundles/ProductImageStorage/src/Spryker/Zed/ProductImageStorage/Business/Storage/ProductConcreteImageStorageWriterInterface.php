<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Business\Storage;

interface ProductConcreteImageStorageWriterInterface
{
    /**
     * @param array $productIds
     *
     * @return void
     */
    public function publish(array $productIds);

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function unpublish(array $productIds);
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business\Storage;

interface ProductSetStorageWriterInterface
{
    /**
     * @param array $productSetIds
     *
     * @return void
     */
    public function publish(array $productSetIds);

    /**
     * @param array $productSetIds
     *
     * @return void
     */
    public function unpublish(array $productSetIds);
}

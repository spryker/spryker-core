<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Storage;

interface ProductLabelDictionaryStorageWriterInterface
{
    /**
     * @param array $productLabelIds
     *
     * @return void
     */
    public function publish(array $productLabelIds = []);

    /**
     * @param array $productLabelIds
     *
     * @return void
     */
    public function unpublish(array $productLabelIds = []);
}

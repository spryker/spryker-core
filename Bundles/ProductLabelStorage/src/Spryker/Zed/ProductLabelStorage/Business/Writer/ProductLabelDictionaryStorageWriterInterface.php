<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Writer;

interface ProductLabelDictionaryStorageWriterInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\Writer\ProductLabelDictionaryStorageWriterInterface::writeProductLabelDictionaryStorageCollection()} instead.
     *
     * @return void
     */
    public function publish();

    /**
     * @return void
     */
    public function writeProductLabelDictionaryStorageCollection(): void;
}

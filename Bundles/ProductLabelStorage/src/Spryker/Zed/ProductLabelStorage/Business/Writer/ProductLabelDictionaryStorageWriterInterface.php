<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Writer;

interface ProductLabelDictionaryStorageWriterInterface
{
    /**
     * @deprecated
     *
     * @return void
     */
    public function publish();

    /**
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeProductLabelDictionaryStorageCollectionByProductLabelEvents(array $eventTransfers): void;
}

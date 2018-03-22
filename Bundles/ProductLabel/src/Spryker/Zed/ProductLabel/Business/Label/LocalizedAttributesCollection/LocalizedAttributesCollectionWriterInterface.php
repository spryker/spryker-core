<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection;

use ArrayObject;

interface LocalizedAttributesCollectionWriterInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[] $localizedAttributesTransferCollection
     *
     * @return void
     */
    public function save(ArrayObject $localizedAttributesTransferCollection);
}

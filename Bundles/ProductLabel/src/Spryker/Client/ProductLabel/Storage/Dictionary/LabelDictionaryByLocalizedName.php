<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage\Dictionary;

use Generated\Shared\Transfer\StorageProductLabelTransfer;

class LabelDictionaryByLocalizedName extends AbstractLabelDictionary
{

    /**
     * @param \Generated\Shared\Transfer\StorageProductLabelTransfer $storageProductLabelTransfer
     *
     * @return int
     */
    protected function getDictionaryKey(StorageProductLabelTransfer $storageProductLabelTransfer)
    {
        return $storageProductLabelTransfer->getName();
    }

}

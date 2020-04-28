<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage\Dictionary;

interface LabelDictionaryInterface
{
    /**
     * @param string $dictionaryKey
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabel(string $dictionaryKey, string $localeName, string $storeName);

    /**
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function getDictionary(string $localeName, string $storeName);
}

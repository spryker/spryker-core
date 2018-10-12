<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;

interface ProductLabelsRestApiToProductLabelStorageClientInterface
{
    /**
     * @param string $labelKey
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByKey(string $labelKey, string $localeName): ?ProductLabelDictionaryItemTransfer;

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract(int $idProductAbstract, string $localeName): array;
}

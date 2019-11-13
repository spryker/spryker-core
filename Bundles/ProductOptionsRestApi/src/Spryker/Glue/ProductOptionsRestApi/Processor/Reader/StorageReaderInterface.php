<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

interface StorageReaderInterface
{
    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[][]
     */
    public function getRestProductOptionAttributesTransfersByProductAbstractSkus(
        array $productAbstractSkus,
        string $localeName
    ): array;

    /**
     * @param string[] $productConcreteSkus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[][]
     */
    public function getRestProductOptionAttributesTransfersByProductConcreteSkus(
        array $productConcreteSkus,
        string $localeName
    ): array;
}

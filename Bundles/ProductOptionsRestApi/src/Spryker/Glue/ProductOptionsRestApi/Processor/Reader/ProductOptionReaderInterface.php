<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

interface ProductOptionReaderInterface
{
    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[][]
     */
    public function getRestProductOptionAttributeTransfersByProductAbstractSkus(array $productAbstractSkus, string $localeName): array;
}

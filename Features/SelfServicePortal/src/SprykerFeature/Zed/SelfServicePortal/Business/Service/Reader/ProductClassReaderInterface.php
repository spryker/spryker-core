<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader;

interface ProductClassReaderInterface
{
    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductClassTransfer>>
     */
    public function getProductClassesByProductConcreteIds(array $productConcreteIds): array;
}

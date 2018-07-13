<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Persistence;

interface ProductAlternativeProductLabelConnectorRepositoryInterface
{
    /**
     * @param string $labelName
     *
     * @return bool
     */
    public function getIsProductLabelActive(string $labelName): bool;

    /**
     * @return array
     */
    public function getProductConcreteIds(): array;

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductConcreteIdsByAbstractProductId(int $idProductAbstract): array;

    /**
     * @return int[]
     */
    public function getProductAbstractIdsForAlternative(): array;
}

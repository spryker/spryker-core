<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Expander;

interface MerchantProductViewDataExpanderInterface
{
    /**
     * @phpstan-param array<string, mixed> $viewData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $viewData
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function expandDataWithMerchantByIdProductAbstract(array $viewData, int $idProductAbstract): array;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductGui\Communication\Expander;

interface MerchantProductViewDataExpanderInterface
{
    /**
     * @param array<string, mixed> $viewData
     * @param int $idProductAbstract
     *
     * @return array<string, mixed>
     */
    public function expandDataWithMerchantByIdProductAbstract(array $viewData, int $idProductAbstract): array;

    /**
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expandDataWithMerchant(array $viewData): array;
}

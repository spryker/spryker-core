<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander;

interface ProductOfferTableActionExpanderInterface
{
    /**
     * @param array<string, mixed> $rowData
     * @param array<string, mixed> $productOfferData
     *
     * @return array<int|string, mixed>
     */
    public function expandData(array $rowData, array $productOfferData): array;
}

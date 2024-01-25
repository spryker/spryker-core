<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Generator;

use Symfony\Component\HttpFoundation\Request;

interface ProductAttributesResetUrlGeneratorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param list<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return array<int, array<string, string>>
     */
    public function generateProductAttributesResetUrlQueryParameters(Request $request, array $productViewTransfers): array;
}

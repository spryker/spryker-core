<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Resolver;

interface ProductConcreteImageInheritanceResolverInterface
{
    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetStorageTransfer[]|null
     */
    public function resolveProductImageSetStorageTransfers(int $idProductConcrete, int $idProductAbstract, string $locale): ?array;
}

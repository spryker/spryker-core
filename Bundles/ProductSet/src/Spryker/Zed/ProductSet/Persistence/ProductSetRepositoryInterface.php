<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Persistence;

use Generated\Shared\Transfer\UrlTransfer;

interface ProductSetRepositoryInterface
{
    /**
     * @param int $idProductSet
     * @param int|null $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findProductSetUrl(int $idProductSet, ?int $idLocale): ?UrlTransfer;
}

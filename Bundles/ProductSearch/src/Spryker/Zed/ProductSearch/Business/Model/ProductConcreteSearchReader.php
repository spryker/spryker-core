<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductConcreteSearchReader extends AbstractProductSearchReader implements ProductConcreteSearchReaderInterface
{
    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function isProductConcreteSearchable($idProductConcrete, ?LocaleTransfer $localeTransfer = null)
    {
        $idLocale = $this->getIdLocale($localeTransfer);

        $searchableCount = $this->productSearchQueryContainer
            ->queryProductSearch()
            ->filterByFkProduct($idProductConcrete)
            ->filterByIsSearchable(true)
            ->filterByFkLocale($idLocale)
            ->count();

        return ($searchableCount > 0);
    }
}

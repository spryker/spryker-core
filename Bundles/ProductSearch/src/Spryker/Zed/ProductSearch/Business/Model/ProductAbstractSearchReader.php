<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductAbstractSearchReader extends AbstractProductSearchReader implements ProductAbstractSearchReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return bool
     */
    public function isProductAbstractSearchable($idProductAbstract, ?LocaleTransfer $localeTransfer = null)
    {
        $idLocale = $this->getIdLocale($localeTransfer);

        $searchableCount = $this->productSearchQueryContainer
            ->queryProductSearch()
            ->filterByIsSearchable(true)
            ->filterByFkLocale($idLocale)
            ->useSpyProductQuery()
                ->filterByFkProductAbstract($idProductAbstract)
            ->endUse()
            ->count();

        return ($searchableCount > 0);
    }
}

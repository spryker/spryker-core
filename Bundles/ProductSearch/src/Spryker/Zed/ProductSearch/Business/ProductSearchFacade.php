<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchBusinessFactory getFactory()
 */
class ProductSearchFacade extends AbstractFacade implements ProductSearchFacadeInterface
{

    /**
     * @api
     * 
     * @return \Generated\Shared\Transfer\ProductSearchAttributeMapTransfer[]
     */
    public function getProductSearchAttributeMap()
    {
        return $this
            ->getFactory()
            ->createSearchProductAttributeMapCollector()
            ->getProductSearchAttributeMap();
    }

    /**
     * @api
     *
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function activateProductSearch($idProduct, array $localeCollection)
    {
        $this->getFactory()
            ->createProductSearchMarker()
            ->activateProductSearch($idProduct, $localeCollection);
    }

    /**
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function deactivateProductSearch($idProduct, array $localeCollection)
    {
        $this->getFactory()
            ->createProductSearchMarker()
            ->deactivateProductSearch($idProduct, $localeCollection);
    }

}

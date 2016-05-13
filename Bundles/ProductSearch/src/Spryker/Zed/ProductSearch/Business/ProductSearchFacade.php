<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchBusinessFactory getFactory()
 */
class ProductSearchFacade extends AbstractFacade implements ProductSearchFacadeInterface
{

    /**
     * @api
     * 
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $attributes
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function mapDynamicProductAttributes(PageMapBuilderInterface $pageMapBuilder, PageMapTransfer $pageMapTransfer, array $attributes)
    {
        return $this
            ->getFactory()
            ->createSearchProductAttributeMapper()
            ->mapDynamicProductAttributes($pageMapBuilder, $pageMapTransfer, $attributes);
    }

    /**
     * @api
     *
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

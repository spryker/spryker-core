<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory getFactory()
 */
class ProductPageSearchFacade extends AbstractFacade implements ProductPageSearchFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()
            ->createProductAbstractPagePublisher()
            ->publish($productAbstractIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     * @param array $pageDataExpanderPluginNames
     *
     * @return void
     */
    public function refresh(array $productAbstractIds, $pageDataExpanderPluginNames = [])
    {
        $this->getFactory()
            ->createProductAbstractPagePublisher()
            ->refresh($productAbstractIds, $pageDataExpanderPluginNames);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->getFactory()
            ->createProductAbstractPagePublisher()
            ->unpublish($productAbstractIds);
    }
}

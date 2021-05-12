<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductConnector\Persistence;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductConnector\Persistence\CmsContentWidgetProductConnectorPersistenceFactory getFactory()
 */
interface CmsContentWidgetProductConnectorQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string[] $skuList
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductIdsBySkuList(array $skuList);
}

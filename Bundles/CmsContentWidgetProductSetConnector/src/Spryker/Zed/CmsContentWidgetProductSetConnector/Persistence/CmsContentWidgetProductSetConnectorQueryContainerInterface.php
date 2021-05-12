<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence\CmsContentWidgetProductSetConnectorPersistenceFactory getFactory()
 */
interface CmsContentWidgetProductSetConnectorQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $keyList
     *
     * @return \Orm\Zed\ProductSet\Persistence\SpyProductSetQuery
     */
    public function queryProductSetIdsByKeyList(array $keyList);
}

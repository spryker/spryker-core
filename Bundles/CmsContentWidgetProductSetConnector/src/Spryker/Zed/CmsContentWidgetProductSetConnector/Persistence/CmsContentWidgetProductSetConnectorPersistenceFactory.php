<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence;

use Spryker\Zed\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence\CmsContentWidgetProductSetConnectorQueryContainerInterface getQueryContainer()
 */
class CmsContentWidgetProductSetConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CmsContentWidgetProductSetConnector\Dependency\QueryContainer\CmsContentWidgetProductSetConnectorProductSetQueryContainerInterface
     */
    public function getProductSetQueryContainer()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductSetConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT_SET);
    }
}

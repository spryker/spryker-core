<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductConnector\Persistence;

use Spryker\Zed\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorConfig getConfig()
 * @method \Spryker\Zed\CmsContentWidgetProductConnector\Persistence\CmsContentWidgetProductConnectorQueryContainer getQueryContainer()
 */
class CmsContentWidgetProductConnectorPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\CmsContentWidgetProductConnector\Dependency\QueryContainer\CmsContentWidgetProductConnectorToProductInterface
     */
    public function getProductQueryContainer()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductConnectorDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector;

use Spryker\Yves\Kernel\AbstractFactory;

class CmsContentWidgetProductSearchConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client\CmsContentWidgetProductSearchConnectorToProductClientInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductSearchConnectorDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Yves\CmsContentWidgetProductSearchConnector\Dependency\Client\CmsContentWidgetProductSearchConnectorToSearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductSearchConnectorDependencyProvider::CLIENT_SEARCH);
    }
}

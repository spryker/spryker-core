<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductConnector;

use Spryker\Yves\Kernel\AbstractFactory;

class CmsContentWidgetProductConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CmsContentWidgetProductConnector\Dependency\Client\CmsContentWidgetProductConnectorToProductInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductConnectorDependencyProvider::PRODUCT_CLIENT);
    }
}

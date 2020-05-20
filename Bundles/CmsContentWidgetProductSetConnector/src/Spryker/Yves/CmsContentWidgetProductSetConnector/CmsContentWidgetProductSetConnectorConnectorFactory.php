<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSetConnector;

use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @deprecated Use {@link \Spryker\Yves\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorFactory} instead.
 */
class CmsContentWidgetProductSetConnectorConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\CmsContentWidgetProductSetConnector\Dependency\Client\CmsContentWidgetProductSetConnectorToProductSetInterface
     */
    public function getProductSetClient()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductSetConnectorDependencyProvider::PRODUCT_SET_CLIENT);
    }

    /**
     * @return \Spryker\Yves\CmsContentWidgetProductSetConnector\Dependency\Client\CmsContentWidgetProductSetConnectorToProductInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(CmsContentWidgetProductSetConnectorDependencyProvider::PRODUCT_CLIENT);
    }
}

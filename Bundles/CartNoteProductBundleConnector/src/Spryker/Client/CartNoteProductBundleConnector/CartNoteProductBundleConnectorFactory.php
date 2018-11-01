<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNoteProductBundleConnector;

use Spryker\Client\Kernel\AbstractFactory;

class CartNoteProductBundleConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CartNoteProductBundleConnector\Dependency\Client\CartNoteProductBundleConnectorToProductBundleClientInterface
     */
    public function getProductBundleClient()
    {
        return $this->getProvidedDependency(CartNoteProductBundleConnectorDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }
}

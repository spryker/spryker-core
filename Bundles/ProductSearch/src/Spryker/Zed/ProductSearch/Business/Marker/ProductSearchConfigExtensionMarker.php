<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Marker;

use Spryker\Shared\ProductSearch\ProductSearchConfig;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface;

class ProductSearchConfigExtensionMarker implements ProductSearchConfigExtensionMarkerInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface $touchFacade
     */
    public function __construct(ProductSearchToTouchInterface $touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @return void
     */
    public function touchProductSearchConfigExtension()
    {
        $this->touchFacade->touchActive(
            ProductSearchConfig::RESOURCE_TYPE_PRODUCT_SEARCH_CONFIG_EXTENSION,
            ProductSearchConfig::PRODUCT_SEARCH_CONFIG_EXPANDER_RESOURCE_ID
        );
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

use Spryker\Client\ContentProduct\Executor\ProductAbstractListTermToProductAbstractListTypeExecutor;
use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\ContentProduct\ContentProductConfig as SharedContentProductConfig;

class ContentProductConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getEnabledTermExecutors(): array
    {
        return [
            SharedContentProductConfig::CONTENT_TERM_PRODUCT_ABSTRACT_LIST => ProductAbstractListTermToProductAbstractListTypeExecutor::class,
        ];
    }
}

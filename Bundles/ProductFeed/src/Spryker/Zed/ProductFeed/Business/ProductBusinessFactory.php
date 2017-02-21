<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductFeed\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductFeed\Business\Exporter\ProductExporter;
use Spryker\Zed\ProductFeed\Business\Exporter\ProductExporterInterface;

/**
 * @method \Spryker\Zed\ProductFeed\ProductFeedConfig getConfig()
 * @method \Spryker\Zed\ProductFeed\Persistence\ProductFeedQueryContainerInterface getQueryContainer()
 */
class ProductFeedBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return ProductExporterInterface
     */
    public function createProductExporter()
    {
        return new ProductExporter(
            $this->getQueryContainer()
        );
    }

}

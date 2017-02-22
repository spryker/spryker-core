<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Business;

use Spryker\Zed\DataFeed\Business\FeedExporter\ProductExporter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\DataFeed\DataFeedConfig getConfig()
 * @method \Spryker\Zed\DataFeed\Persistence\DataFeedQueryContainerInterface getQueryContainer()
 */
class DataFeedBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\DataFeed\Business\FeedExporter\FeedExporterInterface
     */
    public function createProductExporter()
    {
        return new ProductExporter(
            $this->getQueryContainer()
        );
    }

}

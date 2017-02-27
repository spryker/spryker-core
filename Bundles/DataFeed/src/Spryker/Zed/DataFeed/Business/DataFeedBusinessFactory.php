<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Business;

use Spryker\Zed\DataFeed\Business\FeedExporter\CategoryFeedExporter;
use Spryker\Zed\DataFeed\Business\FeedExporter\PriceFeedExporter;
use Spryker\Zed\DataFeed\Business\FeedExporter\ProductFeedExporter;
use Spryker\Zed\DataFeed\Business\FeedExporter\StockFeedExporter;
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
    public function createProductFeedExporter()
    {
        return new ProductFeedExporter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\DataFeed\Business\FeedExporter\FeedExporterInterface
     */
    public function createCategoryFeedExporter()
    {
        return new CategoryFeedExporter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\DataFeed\Business\FeedExporter\FeedExporterInterface
     */
    public function createPriceFeedExporter()
    {
        return new PriceFeedExporter(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\DataFeed\Business\FeedExporter\FeedExporterInterface
     */
    public function createStockFeedExporter()
    {
        return new StockFeedExporter(
            $this->getQueryContainer()
        );
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Communication\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class FactFinderControllerProvider extends AbstractYvesControllerProvider
{

    const ROUTE_FACT_FINDER = 'fact-finder';
    const ROUTE_FACT_FINDER_PRODUCT_DETAIL = 'fact-finder-product-detail';
    const ROUTE_FACT_FINDER_CSV_PATH = 'fact-finder/csv/';
    const ROUTE_FACT_FINDER_SEARCH = 'fact-finder-search';
    const ROUTE_FACT_FINDER_RECOMMEND = 'fact-finder-recommend';
    const ROUTE_FACT_FINDER_TRACK = 'fact-finder-track';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{factfinder}', self::ROUTE_FACT_FINDER, 'FactFinder', 'Index', 'index')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder');

        $this->createController('/{factfinder}/product/{sku}', self::ROUTE_FACT_FINDER_PRODUCT_DETAIL, 'FactFinder', 'Index', 'detail')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder');

        $this->createController('/{factfinder}/search', self::ROUTE_FACT_FINDER_SEARCH, 'FactFinder', 'Index', 'search')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder');

        $this->createController('/{factfinder}/recommendations', self::ROUTE_FACT_FINDER_RECOMMEND, 'FactFinder', 'Index', 'recommendations')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder');

        $this->createController('/{factfinder}/csv/products.csv', self::ROUTE_FACT_FINDER_CSV_PATH . 'products', 'FactFinder', 'csv', 'products')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder')
            ->value('factfinder', 'fact-finder');

        $this->createController('/{factfinder}/csv/categories.csv', self::ROUTE_FACT_FINDER_CSV_PATH . 'categories', 'FactFinder', 'csv', 'categories')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder')
            ->value('factfinder', 'fact-finder');

        $this->createController('/{factfinder}/track', self::ROUTE_FACT_FINDER_TRACK, 'FactFinder', 'Track', 'index')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder');

    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinder\Communication\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class FactFinderControllerProvider extends AbstractYvesControllerProvider
{

    const ROUTE_FACT_FINDER = 'fact-finder';
    const FACT_FINDER_CSV_PATH = 'fact-finder/csv/';

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

        $this->createController('/{factfinder}/csv/products.csv', self::FACT_FINDER_CSV_PATH . 'products', 'FactFinder', 'csv', 'products')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder')
            ->value('factfinder', 'fact-finder');

        $this->createController('/{factfinder}/csv/categories.csv', self::FACT_FINDER_CSV_PATH . 'categories', 'FactFinder', 'csv', 'categories')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder')
            ->value('factfinder', 'fact-finder');

    }

}

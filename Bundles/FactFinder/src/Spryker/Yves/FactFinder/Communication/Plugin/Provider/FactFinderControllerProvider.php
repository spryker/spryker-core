<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\FactFinder\Communication\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class FactFinderControllerProvider extends AbstractYvesControllerProvider
{

    const ROUTE_FACT_FINDER = 'fact-finder';
    const FACT_FINDER_CSV_PATH = 'fact_finder/csv/';

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
            ->value('fact-finder', 'fact-finder');

        $this->createController('/{factfinder}/csv/categories.csv', self::FACT_FINDER_CSV_PATH . 'categories', 'FactFinder', 'csv', 'categories')
            ->assert('factfinder', $allowedLocalesPattern . 'fact-finder|fact-finder')
            ->value('fact-finder', 'fact-finder');

    }

}

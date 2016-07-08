<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\FactFinder\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class FactFinderControllerProvider extends AbstractYvesControllerProvider
{

    const ROUTE_FACT_FINDER = 'fact-finder';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{factfinder}', self::ROUTE_FACT_FINDER, 'FactFinder', 'Index', 'index')
            ->assert('factfinder', $allowedLocalesPattern . 'factfinder|factfinder');
    }

}

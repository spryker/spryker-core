<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\FactFinderGui\Plugin\Provider;

use Pyz\Yves\Application\Plugin\Provider\AbstractServiceProvider;
use Silex\Application;
use Spryker\Shared\Config\Config;
use Spryker\Shared\FactFinder\FactFinderConstants;
use Spryker\Yves\FactFinder\Communication\Plugin\Provider\FactFinderControllerProvider;

class FactFinderSecurityServiceProvider extends AbstractServiceProvider
{

    const FIREWALL_FACT_FINDER = 'fact_finder';
    const ROLE_FACT_FINDER = 'ROLE_FACT_FINDER';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->setSecurityFirewalls($app);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function setSecurityFirewalls(Application &$app)
    {
        $app['security.firewalls'] = array_merge(
            [
                self::FIREWALL_FACT_FINDER => [
                    'pattern' => '^.*/' . FactFinderControllerProvider::ROUTE_FACT_FINDER_CSV_PATH . '*',
                    'http' => true,
                    'users' => [
                        Config::get(FactFinderConstants::CONFIG_BASIC_AUTH_USERNAME) => [
                            self::ROLE_FACT_FINDER,
                            Config::get(FactFinderConstants::CONFIG_BASIC_AUTH_PASSWORD)
                        ]
                    ],
                ]
            ],
            $app['security.firewalls']
        );
    }

}

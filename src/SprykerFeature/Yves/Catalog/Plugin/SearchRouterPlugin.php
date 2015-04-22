<?php

namespace SprykerFeature\Yves\Catalog\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Catalog\Router\SearchRouter;

/**
 * Class SearchRouterPlugin
 * @package SprykerFeature\Yves\Catalog
 */
class SearchRouterPlugin extends AbstractPlugin
{
    /**
     * @param Application $app
     * @param bool $sslEnabled
     * @return SearchRouter
     */
    public function createSearchRouter(Application $app, $sslEnabled = false)
    {
        return $this->getDependencyContainer()->createSearchRouter($app, $sslEnabled);
    }
}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Library\Tracking\DataProvider;

use Silex\Application;

class RequestDataProvider extends AbstractDataProvider
{

    const DATA_PROVIDER_NAME = 'request data provider';

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getStaticPageName()
    {
        $request = ($this->app['request_stack']) ? $this->app['request_stack']->getCurrentRequest() : $this->app['request'];
        \Zend_Debug::dump($request, __CLASS__ . ' LINE: ' . __LINE__ . '<br/><br/>');die();
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 */
class SslServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @throws \Exception
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) use ($app) {
            if ($this->shouldBeSsl($request)) {
                $url = 'https://' . $request->getHttpHost() . $request->getRequestUri();

                return new RedirectResponse($url, 301);
            }
        });
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function shouldBeSsl(Request $request)
    {
        return Config::get(ApplicationConstants::ZED_SSL_ENABLED)
            && !$this->isSecure($request)
            && !$this->isYvesRequest($request)
            && !$this->isExcludedFromRedirection($request, Config::get(ApplicationConstants::ZED_SSL_EXCLUDED));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isYvesRequest(Request $request)
    {
        return (bool)$request->headers->get('X-Yves-Host');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isSecure(Request $request)
    {
        $https = $request->server->get('HTTPS', false);
        $xForwardedProto = $request->server->get('X-Forwarded-Proto', false);

        return ($https && ($https === 'on' || $https === 1) || $xForwardedProto && $xForwardedProto === 'https');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $excluded
     *
     * @return bool
     */
    protected function isExcludedFromRedirection(Request $request, array $excluded)
    {
        return in_array($request->attributes->get('module') . '/' . $request->attributes->get('controller'), $excluded);
    }

}

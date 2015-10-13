<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin\ServiceProvider;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SslServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     *
     * @throws \Exception
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
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function shouldBeSsl(Request $request)
    {
        return Config::get(SystemConfig::ZED_SSL_ENABLED)
            && !$this->isSecure($request)
            && !$this->isYvesRequest($request)
            && !$this->isExcludedFromRedirection($request, Config::get(SystemConfig::ZED_SSL_EXCLUDED));
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function isYvesRequest(Request $request)
    {
        return (bool) $request->headers->get('X-Yves-Host');
    }

    /**
     * @param Request $request
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
     * @param Request $request
     * @param array $excluded
     *
     * @return bool
     */
    protected function isExcludedFromRedirection(Request $request, array $excluded)
    {
        return in_array($request->attributes->get('module') . '/' . $request->attributes->get('controller'), $excluded);
    }

}

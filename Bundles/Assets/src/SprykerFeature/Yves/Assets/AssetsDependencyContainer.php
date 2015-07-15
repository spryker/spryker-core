<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Assets;

use Generated\Yves\Ide\FactoryAutoCompletion\Assets;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Yves\Assets\Model\AssetUrlBuilderInterface;
use SprykerFeature\Yves\Assets\Model\CacheBusterInterface;
use SprykerFeature\Yves\Assets\Model\MediaUrlBuilderInterface;

/**
 * @method Assets getFactory()
 */
class AssetsDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @param bool $isDomainSecured
     *
     * @throws \Exception
     *
     * @return AssetUrlBuilderInterface
     */
    public function createAssetUrlBuilder($isDomainSecured = false)
    {
        $host = Config::get(SystemConfig::HOST_STATIC_ASSETS);

        if ($isDomainSecured) {
            $host = Config::get(SystemConfig::HOST_SSL_STATIC_ASSETS);
        }

        return $this->getFactory()->createModelAssetUrlBuilder($host, $this->createCacheBuster());
    }

    /**
     * @param bool $isDomainSecured
     *
     * @throws \Exception
     *
     * @return MediaUrlBuilderInterface
     */
    public function createMediaUrlBuilder($isDomainSecured = false)
    {
        $host = Config::get(SystemConfig::HOST_STATIC_MEDIA);

        if ($isDomainSecured) {
            $host = Config::get(SystemConfig::HOST_SSL_STATIC_MEDIA);
        }

        return $this->getFactory()->createModelMediaUrlBuilder($host);
    }

    /**
     * @return CacheBusterInterface
     */
    protected function createCacheBuster()
    {
        $cacheBust = 'dev';
        $hashFile = APPLICATION_ROOT_DIR . '/config/Yves/cache_bust.php';

        if (file_exists($hashFile)) {
            $cacheBust = file_get_contents($hashFile);
        }

        return $this->getFactory()->createModelUrlParameterCacheBuster($cacheBust);
    }

}

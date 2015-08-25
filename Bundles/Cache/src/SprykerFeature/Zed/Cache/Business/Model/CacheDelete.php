<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Cache\Business\Model;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Cache\CacheConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
class CacheDelete
{
    /**
     * @var CacheConfig
     */
    protected $config;
    /**
     * @param CacheConfig $config
     */
    public function __construct(CacheConfig $config)
    {
        $this->config = $config;
    }
    /**
     * Deletes all cache files for all stores
     * @return array
     */
    public function deleteAllFiles()
    {
        $rootDirectory = $this->config->getCachePath();
        $stores = $this->config->getAllowedStores();
        $dirs = [];
        foreach ($stores as $store) {
            $dirs[] = str_replace('{STORE}', $store, $rootDirectory);
        }
        $filesystem = new Filesystem();
        $filesystem->remove($dirs);
        return $dirs;
    }
}
<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Cache\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CacheBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Cache\Business\Model\CacheDelete;
use SprykerFeature\Zed\Cache\CacheConfig;

/**
 * @method CacheBusiness getFactory()
 * @method CacheConfig getConfig()
 */
class CacheDependencyContainer extends AbstractBusinessDependencyContainer
{
    /**
     * @return CacheDelete
     */
    public function createCacheDelete()
    {
        $config = $this->getConfig();
        return $this->getFactory()->createModelCacheDelete($config);
    }
}
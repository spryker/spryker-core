<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Spryker\Shared\Library\Collection\LazyCollection;
use Spryker\Shared\Library\DataDirectory;
use Spryker\Shared\Library\Json;

class ClassResolverCache implements ClassResolverCacheInterface
{

    /**
     * @var \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected static $unresolvableCollection;

    /**
     * @return \Spryker\Shared\Library\Collection\CollectionInterface
     */
    protected function getUnresolvableCollection()
    {
        if (self::$unresolvableCollection === null) {
            $callback = function () {
                return $this->getCachedData();
            };

            self::$unresolvableCollection = new LazyCollection($callback);
        }

        return self::$unresolvableCollection;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function classExists($className)
    {
        if ($this->getUnresolvableCollection()->has($className)) {
            return false;
        }

        $exists = class_exists($className);

        if (!$exists) {
            $this->getUnresolvableCollection()->set($className, true);
        }

        return $exists;
    }

    /**
     * @return void
     */
    public function persistCache()
    {
        try {
            file_put_contents($this->getCacheFilename(), Json::encode(
                $this->getUnresolvableCollection()->toArray()
            ));
        }
        catch (\Exception $e) {

        }
    }

    /**
     * @return array
     */
    public function getCachedData()
    {
        try {
            $json = file_get_contents(
                $this->getCacheFilename()
            );

            $data = (array)Json::decode($json);

            return $data ?: [];
        }
        catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @return string
     */
    protected function getCacheFilename()
    {
        return DataDirectory::getLocalStoreSpecificPath('cache/autoloader').'/unresolvable.json';
    }

}

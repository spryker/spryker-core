<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

use Everon\Component\Collection\Lazy;
use Spryker\Shared\Kernel\ClassResolver\Cache\StorageInterface;

class ResolverCache implements ResolverCacheInterface
{
    /**
     * @var \Everon\Component\Collection\CollectionInterface
     */
    protected static $unresolvableCollection;

    /**
     * @var bool
     */
    protected static $modified = false;

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\Cache\StorageInterface
     */
    protected $storage;

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\Cache\StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return \Everon\Component\Collection\CollectionInterface
     */
    protected function getUnresolvableCollection()
    {
        if (self::$unresolvableCollection === null) {
            $callback = function () {
                return $this->getData();
            };

            self::$unresolvableCollection = new Lazy($callback);
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
            $this->markAsUnresolvable($className);
        }

        return $exists;
    }

    /**
     * @param string $className
     *
     * @return void
     */
    protected function markAsUnresolvable($className)
    {
        $this->getUnresolvableCollection()->set($className, true);

        $this->markAsModified();
    }

    /**
     * @return void
     */
    public function persist()
    {
        if (!$this->isModified()) {
            return;
        }

        $this->storage->persist(
            $this->getUnresolvableCollection()->toArray()
        );
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->storage->getData();
    }

    /**
     * @return bool
     */
    protected function isModified()
    {
        return self::$modified;
    }

    /**
     * @return void
     */
    protected function markAsModified()
    {
        self::$modified = true;
    }
}

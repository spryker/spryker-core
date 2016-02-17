<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary;

use Spryker\Client\Glossary\KeyBuilder\GlossaryKeyBuilder;
use Spryker\Client\Glossary\Storage\CachedGlossaryStorage;
use Spryker\Client\Glossary\Storage\GlossaryStorage;
use Spryker\Client\Glossary\Storage\GlossaryStorageCache;
use Spryker\Client\Kernel\AbstractFactory;

class GlossaryFactory extends AbstractFactory
{

    /**
     * @var \Spryker\Client\Glossary\Storage\GlossaryStorageInterface[]
     */
    protected static $translator = [];

    /**
     * @var \Spryker\Client\Glossary\Storage\CachedGlossaryStorageInterface[]
     */
    protected static $cachedTranslator = [];

    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    public function createTranslator($localeName)
    {
        return $this->getTranslatorInstance($localeName);
    }

    /**
     * @param string $localeName
     * @param string $cacheKey
     *
     * @return \Spryker\Client\Glossary\Storage\CachedGlossaryStorageInterface
     */
    public function createCachedTranslator($localeName, $cacheKey)
    {
        return $this->getCachedTranslatorInstance($localeName, $cacheKey);
    }

    /**
     * @param $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    protected function getTranslatorInstance($localeName)
    {
        if (!isset(static::$translator[$localeName])) {
            static::$translator[$localeName] = $this->createGlossaryStorage($localeName);
        }

        return static::$translator[$localeName];
    }

    /**
     * @param $localeName
     * @param $cacheKey
     *
     * @return \Spryker\Client\Glossary\Storage\CachedGlossaryStorageInterface
     */
    protected function getCachedTranslatorInstance($localeName, $cacheKey)
    {
        if (!isset(static::$cachedTranslator[$localeName])) {
            static::$cachedTranslator[$localeName] = $this->createCachedGlossaryStorage($localeName, $cacheKey);
        }

        return static::$cachedTranslator[$localeName];
    }

    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    protected function createGlossaryStorage($localeName)
    {
        return new GlossaryStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $localeName
        );
    }

    /**
     * @param string $localeName
     * @param string $cacheKey
     *
     * @return \Spryker\Client\Glossary\Storage\CachedGlossaryStorageInterface
     */
    protected function createCachedGlossaryStorage($localeName, $cacheKey)
    {
        return new CachedGlossaryStorage(
            $this->createGlossaryStorageCache($cacheKey, $localeName),
            $this->getStorage(),
            $this->createKeyBuilder(),
            $localeName
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new GlossaryKeyBuilder();
    }

    /**
     * @param string $cacheKey
     * @param string $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryCacheInterface
     */
    protected function createGlossaryStorageCache($cacheKey, $localeName)
    {
        return new GlossaryStorageCache($this->getStorage(), $this->createKeyBuilder(), $cacheKey, $localeName);
    }

}

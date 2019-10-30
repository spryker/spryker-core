<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\Cache\Cache;

use Codeception\Test\Unit;
use Spryker\Shared\Twig\Cache\Cache\FilesystemCache;
use Spryker\Shared\Twig\Cache\CacheInterface;
use Spryker\Shared\Twig\Cache\CacheLoader\FilesystemCacheLoader;
use Spryker\Shared\Twig\Cache\CacheWriter\FilesystemCacheWriter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group Cache
 * @group Cache
 * @group FilesystemCacheTest
 * Add your own group annotations below this line
 */
class FilesystemCacheTest extends Unit
{
    public const EXISTING_CACHE_KEY = 'key';
    public const EXISTING_CACHE_VALUE = 'value';

    public const NEW_CACHE_KEY = 'new key';
    public const NEW_CACHE_VALUE = 'new value';

    public const NOT_EXISTING_CACHE_KEY = 'not existing key';
    public const INVALID_CACHE_KEY = 'invalid value';

    /**
     * @return void
     */
    public function setUp()
    {
        if (is_file($this->getCacheFile())) {
            unlink($this->getCacheFile());
        }

        $this->buildTestCacheFile();
    }

    /**
     * @return string
     */
    protected function getCacheFile()
    {
        $cacheFile = $this->getFixtureDirectory() . 'cache.php';

        return $cacheFile;
    }

    /**
     * @return string
     */
    protected function getFixtureDirectory()
    {
        //TODO: THis needs to be moved to tmp dir instead.
        $directory = __DIR__ . DIRECTORY_SEPARATOR . 'test_files' . DIRECTORY_SEPARATOR;

        return $directory;
    }

    /**
     * @return void
     */
    protected function buildTestCacheFile()
    {
        file_put_contents($this->getCacheFile(), <<<TXT
<?php return [
    'key' => 'value',
    'invalid key' => false,
];

TXT
        );
    }

    /**
     * @return void
     */
    public function testEnabledPathCacheCanBeInstantiated()
    {
        $this->assertInstanceOf(CacheInterface::class, $this->getEnabledCache());
    }

    /**
     * @return void
     */
    public function testDisabledPathCacheCanBeInstantiated()
    {
        $this->assertInstanceOf(CacheInterface::class, $this->getDisabledCache());
    }

    /**
     * @return void
     */
    public function testHasReturnsFalseIfCacheHasKeyButCacheIsDisabled()
    {
        $this->assertFalse($this->getDisabledCache()->has(static::EXISTING_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testHasReturnsTrueIfCacheHasKey()
    {
        $this->assertTrue($this->getEnabledCache()->has(static::EXISTING_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testGetReturnsFalseIfCacheIsDisabled()
    {
        $this->assertFalse($this->getDisabledCache()->get(static::EXISTING_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testGetReturnsFalseIfKeyNotInCache()
    {
        $this->assertFalse($this->getEnabledCache()->get(static::NOT_EXISTING_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testGetReturnsValueIfCacheHasKey()
    {
        $this->assertSame(static::EXISTING_CACHE_VALUE, $this->getEnabledCache()->get(static::EXISTING_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testSetAddsValueToCache()
    {
        $cache = $this->getEnabledCache();
        $cache->set(static::NEW_CACHE_KEY, static::NEW_CACHE_VALUE);

        $this->assertSame(static::NEW_CACHE_VALUE, $cache->get(static::NEW_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testSetReturnFluentInterface()
    {
        $cache = $this->getEnabledCache()->set(static::NEW_CACHE_KEY, static::NEW_CACHE_VALUE);
        $this->assertInstanceOf(CacheInterface::class, $cache);
    }

    /**
     * @return void
     */
    public function testIsValidReturnsFalseIfCacheIsDisabled()
    {
        $this->assertFalse($this->getDisabledCache()->isValid(static::EXISTING_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testIsValidReturnsFalseIfKeyNotInCache()
    {
        $this->assertFalse($this->getEnabledCache()->get(static::NOT_EXISTING_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testIsValidReturnsTrueIfValueNotSetToFalse()
    {
        $this->assertTrue($this->getEnabledCache()->isValid(static::EXISTING_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testIsValidReturnsFalseIfValueSetToFalse()
    {
        $this->assertFalse($this->getEnabledCache()->isValid(static::INVALID_CACHE_KEY));
    }

    /**
     * @return void
     */
    public function testWhenDestructIsCalledCacheIsWrittenToFile()
    {
        $cache = $this->getEnabledCache();
        $cache->set(static::NEW_CACHE_KEY, static::NEW_CACHE_VALUE);

        $cache->__destruct();
        $cache = $this->getEnabledCache();
        $this->assertTrue($cache->has(static::NEW_CACHE_KEY), 'Cache does not contain expected key, maybe cache was not written to file');
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\Cache\FilesystemCache
     */
    protected function getEnabledCache()
    {
        $pathToCacheFile = $this->getCacheFile();

        return new FilesystemCache(
            $this->getFilesystemCacheLoader($pathToCacheFile),
            $this->getFilesystemCacheWriter($pathToCacheFile),
            true
        );
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\Cache\FilesystemCache
     */
    protected function getDisabledCache()
    {
        $pathToCacheFile = $this->getCacheFile();

        return new FilesystemCache(
            $this->getFilesystemCacheLoader($pathToCacheFile),
            $this->getFilesystemCacheWriter($pathToCacheFile),
            false
        );
    }

    /**
     * @param string $pathToCacheFile
     *
     * @return \Spryker\Shared\Twig\Cache\CacheLoader\FilesystemCacheLoader
     */
    protected function getFilesystemCacheLoader(string $pathToCacheFile)
    {
        return new FilesystemCacheLoader($pathToCacheFile);
    }

    /**
     * @param string $pathToCacheFile
     *
     * @return \Spryker\Shared\Twig\Cache\CacheWriter\FilesystemCacheWriter
     */
    protected function getFilesystemCacheWriter(string $pathToCacheFile)
    {
        return new FilesystemCacheWriter($pathToCacheFile, 0777);
    }
}

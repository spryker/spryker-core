<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Twig\Cache\CacheInterface;
use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceBridge;
use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractor;
use Spryker\Shared\Twig\TwigFilesystemLoader;
use SprykerTest\Shared\Twig\Stub\CacheStub;
use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group TwigFilesystemLoaderTest
 * Add your own group annotations below this line
 */
class TwigFilesystemLoaderTest extends Unit
{
    public const PATH_TO_ZED_PROJECT = __DIR__ . '/Fixtures/src/ProjectNamespace/Zed/Bundle/Presentation';
    public const PATH_TO_ZED_CORE = __DIR__ . '/Fixtures/vendor/spryker/spryker/Bundles/*/src/CoreNamespace/Zed/%s/Presentation';
    public const CONTENT_CACHED_FILE = 'cached file' . PHP_EOL;
    public const CONTENT_PROJECT_ZED_FILE = 'project zed file' . PHP_EOL;
    public const CONTENT_CORE_ZED_FILE = 'core zed non split file' . PHP_EOL;
    public const TEMPLATE_NAME = '@Bundle/Controller/index.twig';

    /**
     * @return void
     */
    public function testCanBeConstructedWithTemplatePathsArray()
    {
        $templatePaths = [];
        $filesystemLoader = new TwigFilesystemLoader($templatePaths, $this->getCacheStub(), $this->getTemplateNameExtractor());

        $this->assertInstanceOf(LoaderInterface::class, $filesystemLoader);
    }

    /**
     * @return void
     */
    public function testGetSourceReturnsFileFromCache()
    {
        $cache = $this->getCacheStub();
        $cache->set('@CachedBundle/Controller/index.twig', __DIR__ . '/Fixtures/cache/cached.twig');
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_ZED_PROJECT, $cache);

        $this->assertSame(static::CONTENT_CACHED_FILE, $filesystemLoader->getSource('@CachedBundle/Controller/index.twig'));
    }

    /**
     * @return void
     */
    public function testGetSourceThrowsExceptionWhenPathInCacheMarkedAsInvalid()
    {
        $cache = $this->getCacheStub();
        $cache->set('@Invalid/Controller/index.twig', false);
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_ZED_PROJECT, $cache);

        $this->expectException(LoaderError::class);
        $this->expectExceptionMessage('Unable to find template "@Invalid/Controller/index.twig" (cached).');

        $filesystemLoader->getSource('@Invalid/Controller/index.twig');
    }

    /**
     * @return string[][]
     */
    public function templateNameContentProvider(): array
    {
        return [
            ['@Bundle/Controller/index.twig', static::CONTENT_PROJECT_ZED_FILE],
            ['/Bundle/Controller/index.twig', static::CONTENT_PROJECT_ZED_FILE],
            ['Bundle/Controller/index.twig', static::CONTENT_PROJECT_ZED_FILE],
            ['@ProjectNamespace:Bundle/Controller/index.twig', static::CONTENT_PROJECT_ZED_FILE],
            ['/ProjectNamespace:Bundle/Controller/index.twig', static::CONTENT_PROJECT_ZED_FILE],
            ['ProjectNamespace:Bundle/Controller/index.twig', static::CONTENT_PROJECT_ZED_FILE],
            ['@CoreNamespace:Bundle/Controller/index.twig', static::CONTENT_CORE_ZED_FILE],
            ['/CoreNamespace:Bundle/Controller/index.twig', static::CONTENT_CORE_ZED_FILE],
            ['CoreNamespace:Bundle/Controller/index.twig', static::CONTENT_CORE_ZED_FILE],
        ];
    }

    /**
     * @dataProvider templateNameContentProvider
     *
     * @param string $templateName
     * @param string $expectedContent
     *
     * @return void
     */
    public function testLoadReturnsFileContent(string $templateName, string $expectedContent): void
    {
        $filesystemLoader = $this->getFilesystemLoader([
            static::PATH_TO_ZED_PROJECT,
            static::PATH_TO_ZED_CORE,
        ]);

        $content = $filesystemLoader->getSource($templateName);

        $this->assertSame($expectedContent, $content);
    }

    /**
     * @return void
     */
    public function testGetSourceThrowsExceptionWhenFileNotExists()
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_ZED_PROJECT);

        $this->expectException(LoaderError::class);
        $this->expectExceptionMessage('Unable to find template "NotExistent/index.twig');

        $filesystemLoader->getSource('@Bundle/NotExistent/index.twig');
    }

    /**
     * @return void
     */
    public function testGetSourceThrowsExceptionWhenNameDoesNotContainControllerAndTemplateNameInfo()
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_ZED_PROJECT);

        $this->expectException(LoaderError::class);
        $this->expectExceptionMessage('Malformed bundle template name "@Bundle" (expecting "@Bundle/template_name").');

        $filesystemLoader->getSource('@Bundle');
    }

    /**
     * @return void
     */
    public function testIsFreshReturnsTrueWhenFileIsFresh()
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_ZED_PROJECT);
        $time = filemtime(static::PATH_TO_ZED_PROJECT . '/Controller/index.twig') + 10;

        $this->assertTrue($filesystemLoader->isFresh(static::TEMPLATE_NAME, $time));
    }

    /**
     * @return void
     */
    public function testIsFreshReturnsFalseWhenFileIsNotFresh()
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_ZED_PROJECT);
        $time = filemtime(static::PATH_TO_ZED_PROJECT . '/Controller/index.twig') - 10;

        $this->assertFalse($filesystemLoader->isFresh(static::TEMPLATE_NAME, $time));
    }

    /**
     * @return void
     */
    public function testGetCacheKeyReturnsPathToTemplate()
    {
        $filesystemLoader = $this->getFilesystemLoader(static::PATH_TO_ZED_PROJECT);
        $cacheKey = $filesystemLoader->getCacheKey(static::TEMPLATE_NAME);

        $this->assertSame(static::PATH_TO_ZED_PROJECT . '/Controller/index.twig', $cacheKey);
    }

    /**
     * @return \Spryker\Shared\Twig\Cache\CacheInterface
     */
    protected function getCacheStub()
    {
        return new CacheStub();
    }

    /**
     * @return \Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractorInterface
     */
    protected function getTemplateNameExtractor()
    {
        $twigToUtilTextBridge = new TwigToUtilTextServiceBridge(new UtilTextService());
        $templateNameExtractor = new TemplateNameExtractor($twigToUtilTextBridge);

        return $templateNameExtractor;
    }

    /**
     * @param string|array $path
     * @param \Spryker\Shared\Twig\Cache\CacheInterface|null $cache
     *
     * @return \Spryker\Shared\Twig\TwigFilesystemLoader
     */
    protected function getFilesystemLoader($path, ?CacheInterface $cache = null)
    {
        if (!$cache) {
            $cache = $this->getCacheStub();
        }

        return new TwigFilesystemLoader((array)$path, $cache, $this->getTemplateNameExtractor());
    }
}

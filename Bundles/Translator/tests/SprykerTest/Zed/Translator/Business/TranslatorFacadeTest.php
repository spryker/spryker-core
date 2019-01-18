<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Translator\Business;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Translator\Business\TranslatorBusinessFactory;
use Spryker\Zed\Translator\TranslatorConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Translator
 * @group Business
 * @group Facade
 * @group TranslatorFacadeTest
 * Add your own group annotations below this line
 */
class TranslatorFacadeTest extends Unit
{
    protected const TEST_CACHE_DIR = __DIR__ . '/../../../../_output/cache/';

    protected const TEST_TRANSLATION_FILE_DIR = [
        __DIR__ . '/../../../../_data/',
    ];

    protected const LOCALE = 'de_DE';

    /**
     * @var \SprykerTest\Zed\Translator\TranslatorBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Translator\Business\TranslatorFacade
     */
    protected $translatorFacade;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new Finder();

        $translatorFactory = Stub::make(TranslatorBusinessFactory::class, [
            'getConfig' => Stub::make(TranslatorConfig::class, [
                'getCacheDir' => static::TEST_CACHE_DIR,
                'getTranslationFilePathPatterns' => static::TEST_TRANSLATION_FILE_DIR,
            ]),
            'getFileSystem' => new Filesystem(),
            'getFinder' => $this->finder,
            'getApplication' => new Application(['locale' => static::LOCALE]),
        ]);

        $this->translatorFacade = $this->tester->getLocator()->translator()->facade();
        $this->translatorFacade->setFactory($translatorFactory);
    }

    /**
     * @return void
     */
    public function testGenerateTranslationCache(): void
    {
        // Arrange
        $locales = Store::getInstance()->getLocales();

        // Act
        $this->translatorFacade->generateTranslationCache();

        // Assert
        $finder = clone $this->finder;
        $finder->in(static::TEST_CACHE_DIR)->depth(0);
        $this->assertEquals(count($locales) * 2, $finder->count());
    }

    /**
     * @return void
     */
    public function testClearTranslationCache(): void
    {
        // Act
        $this->translatorFacade->cleanTranslationCache();

        // Assert
        $finder = clone $this->finder;
        $finder->in(static::TEST_CACHE_DIR)->depth(0);
        $this->assertEquals(0, $finder->count());
    }
}

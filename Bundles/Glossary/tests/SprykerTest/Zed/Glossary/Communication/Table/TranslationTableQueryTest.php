<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Communication\Table;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Twig\Environment;
use Twig\Loader\ChainLoader;
use Twig\Loader\LoaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Communication
 * @group Table
 * @group TranslationTableQueryTest
 * Add your own group annotations below this line
 */
class TranslationTableQueryTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     */
    public const SERVICE_TWIG = 'twig';

    protected const TEST_LOCALE_1 = 'xxx';
    protected const TEST_LOCALE_2 = 'yyy';
    protected const TEST_GLOSSARY_KEY = 'test_glossary_key';

    /**
     * @var \SprykerTest\Zed\Glossary\GlossaryCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->registerTwigServiceMock();
    }

    /**
     * @return void
     */
    public function testFetchDataCollectsCorrectMerchantData(): void
    {
        // Arrange
        SpyGlossaryTranslationQuery::create()->deleteAll();
        SpyGlossaryKeyQuery::create()->deleteAll();

        $localeTransfer1 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_1]);
        $localeTransfer2 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_2]);
        $localeNames = [
            $localeTransfer1->getIdLocale() => $localeTransfer1->getLocaleName(),
            $localeTransfer2->getIdLocale() => $localeTransfer2->getLocaleName(),
        ];

        $idGlossaryKey1 = $this->tester->haveTranslation([KeyTranslationTransfer::LOCALES => $localeNames]);
        $idGlossaryKey2 = $this->tester->haveTranslation([KeyTranslationTransfer::LOCALES => $localeNames]);

        $translationTableMock = new TranslationTableMock(
            SpyGlossaryKeyQuery::create(),
            SpyGlossaryTranslationQuery::create(),
            $localeNames
        );

        // Act
        $result = $translationTableMock->fetchData();

        // Assert
        $this->assertNotEmpty($result);
        $resultGlossaryKeyIds = array_column($result, SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY);
        $this->assertContains($idGlossaryKey1, $resultGlossaryKeyIds);
        $this->assertContains($idGlossaryKey2, $resultGlossaryKeyIds);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected function getMerchantGuiToMerchantFacadeMock(): MerchantGuiToMerchantFacadeInterface
    {
        return $this->getMockBuilder(MerchantGuiToMerchantFacadeInterface::class)->getMock();
    }

    /**
     * @return void
     */
    protected function registerTwigServiceMock(): void
    {
        $this->tester->getContainer()->set(static::SERVICE_TWIG, $this->getTwigMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected function getTwigMock(): Environment
    {
        $twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
        $twigMock->method('render')
            ->willReturn('Fully rendered template');
        $twigMock->method('getLoader')->willReturn($this->getChainLoader());

        return $twigMock;
    }

    /**
     * @return \Twig\Loader\LoaderInterface
     */
    protected function getChainLoader(): LoaderInterface
    {
        return new ChainLoader();
    }
}

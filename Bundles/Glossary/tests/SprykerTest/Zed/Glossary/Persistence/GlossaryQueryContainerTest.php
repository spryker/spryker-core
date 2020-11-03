<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Glossary\Persistence\GlossaryPersistenceFactory;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Persistence
 * @group GlossaryQueryContainerTest
 * Add your own group annotations below this line
 */
class GlossaryQueryContainerTest extends Unit
{
    protected const TEST_LOCALE_1 = 'xxx';
    protected const TEST_LOCALE_2 = 'yyy';
    protected const TEST_GLOSSARY_KEY = 'test_glossary_key';

    /**
     * @var \SprykerTest\Zed\Glossary\GlossaryPersistenceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $glossaryPersistenceFactory = new GlossaryPersistenceFactory();
        $this->glossaryQueryContainer = new GlossaryQueryContainer();
        $this->glossaryQueryContainer->setFactory($glossaryPersistenceFactory);
    }

    /**
     * @return void
     */
    public function testQueryGlossaryKeyTranslationsByLocaleReturnsCorrectData(): void
    {
        //Arrange
        $localeTransfer1 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_1]);
        $localeTransfer2 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_2]);
        $localeNames = [
            $localeTransfer1->getLocaleName(),
            $localeTransfer2->getLocaleName(),
        ];

        $idGlossaryKey = $this->tester->haveTranslation([KeyTranslationTransfer::LOCALES => $localeNames]);

        //Act
        $result = $this->glossaryQueryContainer
            ->queryGlossaryKeyTranslationsByLocale($idGlossaryKey, $localeNames)
            ->find()->toArray();

        //Assert
        $this->assertCount(2, $result);
        $this->assertEmpty(array_diff($localeNames, array_column($result, GlossaryQueryContainer::LOCALE)));
    }

    /**
     * @return void
     */
    public function testQueryKeysAndTranslationsForEachLanguageReturnsCorrectData(): void
    {
        $localeTransfer1 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_1]);
        $localeTransfer2 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_2]);

        $this->tester->haveTranslation([
        KeyTranslationTransfer::LOCALES => [
            $localeTransfer1->getLocaleName(),
            $localeTransfer2->getLocaleName(),
        ]]);

        //Act
        $result = $this->glossaryQueryContainer
            ->queryKeysAndTranslationsForEachLanguage([
                $localeTransfer1->getIdLocale(),
                $localeTransfer2->getIdLocale(),
            ])->find()->toArray();

        //Assert
        $arrayKeyPattern = 'translation_%d_value';
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey(sprintf($arrayKeyPattern, $localeTransfer1->getIdLocale()), $result[0]);
        $this->assertArrayHasKey(sprintf($arrayKeyPattern, $localeTransfer2->getIdLocale()), $result[0]);
    }
}

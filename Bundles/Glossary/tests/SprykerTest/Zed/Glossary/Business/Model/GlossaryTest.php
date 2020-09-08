<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Glossary\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\TranslationTransfer;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Glossary
 * @group Business
 * @group Model
 * @group GlossaryTest
 * Add your own group annotations below this line
 */
class GlossaryTest extends Unit
{
    /**
     * @var \Spryker\Zed\Glossary\Business\GlossaryFacade|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer
     */
    protected $glossaryQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainer
     */
    protected $touchQueryContainer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->glossaryQueryContainer = new GlossaryQueryContainer();
        $this->localeFacade = new LocaleFacade();
        $this->touchQueryContainer = new TouchQueryContainer();
        $this->glossaryFacade = new GlossaryFacade();
    }

    /**
     * @return void
     */
    public function testCreateKeyInsertsSomething(): void
    {
        $keyQuery = $this->glossaryQueryContainer->queryKeys();
        $keyCountBeforeCreation = $keyQuery->count();

        $this->glossaryFacade->createKey('ATestKey');
        $keyCountAfterCreation = $keyQuery->count();
        $this->assertTrue($keyCountAfterCreation > $keyCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testUpdateKeyMustSaveNewKeyInDatabase(): void
    {
        $keyQuery = $this->glossaryQueryContainer->queryKeys();
        $keyId = $this->glossaryFacade->createKey('ATestKey2');

        $this->assertSame('ATestKey2', $keyQuery->findPk($keyId)->getKey());
        $this->glossaryFacade->updateKey('ATestKey2', 'ATestKey3');
        $this->assertSame('ATestKey3', $keyQuery->findPk($keyId)->getKey());
    }

    /**
     * @return void
     */
    public function testHasKeyReturnsRightValue(): void
    {
        $this->glossaryFacade->createKey('SomeNewKey');
        $this->assertTrue($this->glossaryFacade->hasKey('SomeNewKey'));
    }

    /**
     * @return void
     */
    public function testDeleteKeyDeletesSomething(): void
    {
        $specificKeyQuery = $this->glossaryQueryContainer->queryKey('KeyToBeDeleted');

        $this->glossaryFacade->createKey('KeyToBeDeleted');
        $this->assertTrue($specificKeyQuery->findOne()->getIsActive());

        $this->glossaryFacade->deleteKey('KeyToBeDeleted');

        $this->assertFalse($specificKeyQuery->findOne()->getIsActive());
    }

    /**
     * @return void
     */
    public function testCreateTranslationInsertsSomething(): void
    {
        $translationQuery = $this->glossaryQueryContainer->queryTranslations();
        $this->glossaryFacade->createKey('AKey');
        $locale = $this->localeFacade->createLocale('Local');

        $translationCountBeforeCreation = $translationQuery->count();
        $this->glossaryFacade->createTranslation('AKey', $locale, 'ATranslation', true);
        $translationCountAfterCreation = $translationQuery->count();

        $this->assertTrue($translationCountAfterCreation > $translationCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testCreateAndTouchTranslationInsertsSomethingAndTouchesIt(): void
    {
        $translationQuery = $this->glossaryQueryContainer->queryTranslations();
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');
        $this->glossaryFacade->createKey('AKey2');
        $locale = $this->localeFacade->createLocale('Locaz');

        $translationCountBeforeCreation = $translationQuery->count();
        $touchCountBeforeCreation = $touchQuery->count();
        $this->glossaryFacade->createAndTouchTranslation('AKey2', $locale, 'ATranslation', true);
        $translationCountAfterCreation = $translationQuery->count();
        $touchCountAfterCreation = $touchQuery->count();

        $this->assertTrue($translationCountAfterCreation > $translationCountBeforeCreation);
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testUpdateTranslationUpdatesSomething(): void
    {
        $locale = $this->localeFacade->createLocale('Local');

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('AnotherKey', $locale->getLocaleName());
        $this->glossaryFacade->createKey('AnotherKey');
        $this->glossaryFacade->createTranslation('AnotherKey', $locale, 'Some Translation', true);

        $this->assertSame('Some Translation', $specificTranslationQuery->findOne()->getValue());

        $this->glossaryFacade->updateTranslation('AnotherKey', $locale, 'Some other Translation', true);

        $this->assertSame('Some other Translation', $specificTranslationQuery->findOne()->getValue());
    }

    /**
     * @return void
     */
    public function testUpdateAndTouchTranslationUpdatesSomethingAndTouchesIt(): void
    {
        $locale = $this->localeFacade->createLocale('Locaz');
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('AnotherKey2', $locale->getLocaleName());
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');
        $this->glossaryFacade->createKey('AnotherKey2');
        $this->glossaryFacade->createTranslation('AnotherKey2', $locale, 'Some Translation', true);

        $this->assertSame('Some Translation', $specificTranslationQuery->findOne()->getValue());
        $touchCountBeforeCreation = $touchQuery->count();

        $this->glossaryFacade->updateAndTouchTranslation('AnotherKey2', $locale, 'Some other Translation', true);
        $touchCountAfterCreation = $touchQuery->count();

        $this->assertSame('Some other Translation', $specificTranslationQuery->findOne()->getValue());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testDeleteTranslationDeletesSoftly(): void
    {
        $locale = $this->localeFacade->createLocale('yx_qw');
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('KeyWithTranslation', $locale->getLocaleName());
        $this->glossaryFacade->createKey('KeyWithTranslation');
        $this->glossaryFacade->createTranslation('KeyWithTranslation', $locale, 'A Translation...', true);
        $this->assertTrue($specificTranslationQuery->findOne()->getIsActive());

        $this->glossaryFacade->deleteTranslation('KeyWithTranslation', $locale);

        $this->assertFalse($specificTranslationQuery->findOne()->getIsActive());
    }

    /**
     * @return void
     */
    public function testSaveTranslationDoesACreate(): void
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey');
        $locale = $this->localeFacade->createLocale('ab_xy');
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $locale->getIdLocale());

        $transferTranslation = new TranslationTransfer();
        $transferTranslation->setFkGlossaryKey($keyId);
        $transferTranslation->setFkLocale($locale->getIdLocale());
        $transferTranslation->setValue('some Value');
        $transferTranslation->setIsActive(true);

        $this->assertSame(0, $specificTranslationQuery->count());

        $transferTranslation = $this->glossaryFacade->saveTranslation($transferTranslation);

        $this->assertSame(1, $specificTranslationQuery->count());
        $this->assertNotNull($transferTranslation->getIdGlossaryTranslation());
    }

    /**
     * @return void
     */
    public function testSaveTranslationDoesAnUpdate(): void
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey2');
        $locale = $this->localeFacade->createLocale('ab_yz');
        $transferTranslation = $this->glossaryFacade->createTranslation(
            'SomeNonExistentKey2',
            $locale,
            'some translation'
        );
        $this->assertNotNull($transferTranslation->getIdGlossaryTranslation());

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $locale->getIdLocale());
        $this->assertSame(1, $specificTranslationQuery->count());

        $transferTranslation->setValue('someOtherTranslation');

        $this->glossaryFacade->saveTranslation($transferTranslation);

        $this->assertSame('someOtherTranslation', $specificTranslationQuery->findOne()->getValue());
    }

    /**
     * @return void
     */
    public function testSaveAndTouchTranslationDoesATouchForCreation(): void
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey3');
        $localeId = $this->localeFacade->createLocale('ab_ef')->getIdLocale();
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $localeId);
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');

        $transferTranslation = new TranslationTransfer();
        $transferTranslation->setFkGlossaryKey($keyId);
        $transferTranslation->setFkLocale($localeId);
        $transferTranslation->setValue('some Value');
        $transferTranslation->setIsActive(true);

        $this->assertSame(0, $specificTranslationQuery->count());
        $touchCountBeforeCreation = $touchQuery->count();

        $transferTranslation = $this->glossaryFacade->saveAndTouchTranslation($transferTranslation);

        $touchCountAfterCreation = $touchQuery->count();

        $this->assertSame(1, $specificTranslationQuery->count());
        $this->assertNotNull($transferTranslation->getIdGlossaryTranslation());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testSaveAndTouchTranslationDoesATouchForUpdate(): void
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey4');
        $locale = $this->localeFacade->createLocale('ab_fg');
        $transferTranslation = $this->glossaryFacade->createTranslation('SomeNonExistentKey4', $locale, 'some Value', true);

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $locale->getIdLocale());
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');

        $this->assertSame(1, $specificTranslationQuery->count());
        $touchCountBeforeCreation = $touchQuery->count();

        $transferTranslation->setValue('setSomeOtherTranslation');
        $this->glossaryFacade->saveAndTouchTranslation($transferTranslation);

        $touchCountAfterCreation = $touchQuery->count();

        $this->assertSame('setSomeOtherTranslation', $specificTranslationQuery->findOne()->getValue());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testCreatingTranslationForCurrentLocaleInsertsSomething(): void
    {
        $translationQuery = $this->glossaryQueryContainer->queryTranslations();
        $this->glossaryFacade->createKey('SomeNonExistingKey5');

        $translationCountBeforeCreation = $translationQuery->count();
        $translation = $this->glossaryFacade->createTranslationForCurrentLocale('SomeNonExistingKey5', 'WhateverTranslation');
        $translationCountAfterCreation = $translationQuery->count();

        $this->assertTrue($translationCountAfterCreation > $translationCountBeforeCreation);

        $this->assertNotNull($translation->getIdGlossaryTranslation());
    }

    /**
     * @return void
     */
    public function testTouchTranslationForKeyAndCurrentLocale(): void
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey6');
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $transferTranslation = $this->glossaryFacade->createTranslationForCurrentLocale('SomeNonExistentKey6', 'some value', true);

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $localeTransfer->getIdLocale());
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');

        $this->assertSame(1, $specificTranslationQuery->count());
        $touchCountBeforeCreation = $touchQuery->count();

        $transferTranslation->setValue('setSomeOtherTranslation');
        $this->glossaryFacade->saveTranslation($transferTranslation);
        $this->glossaryFacade->touchTranslationForKeyId($keyId);

        $touchCountAfterCreation = $touchQuery->count();

        $this->assertSame('setSomeOtherTranslation', $specificTranslationQuery->findOne()->getValue());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testTouchTranslationForKeyAndCustomLocale(): void
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey7');
        $localeTransfer = $this->localeFacade->createLocale('ab_fg');
        $transferTranslation = $this->glossaryFacade->createTranslation('SomeNonExistentKey7', $localeTransfer, 'some value', true);

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $localeTransfer->getIdLocale());
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');

        $this->assertSame(1, $specificTranslationQuery->count());
        $touchCountBeforeCreation = $touchQuery->count();

        $transferTranslation->setValue('setSomeOtherTranslation');
        $this->glossaryFacade->saveTranslation($transferTranslation);
        $this->glossaryFacade->touchTranslationForKeyId($keyId, $localeTransfer);

        $touchCountAfterCreation = $touchQuery->count();

        $this->assertSame('setSomeOtherTranslation', $specificTranslationQuery->findOne()->getValue());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }
}

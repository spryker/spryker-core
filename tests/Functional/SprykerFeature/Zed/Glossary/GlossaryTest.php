<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Functional\SprykerFeature\Zed\Glossary;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\Factory;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;
use SprykerFeature\Shared\Glossary\Transfer\Translation;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainer;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

/**
 * @group GlossaryTest
 */
class GlossaryTest extends Test
{
    /**
     * @var GlossaryFacade $glossaryFacade
     */
    private $glossaryFacade;

    /**
     * @var LocaleFacade
     */
    private $localeFacade;

    /**
     * @var GlossaryQueryContainerInterface $glossaryQueryContainer
     */
    private $glossaryQueryContainer;

    /**
     * @var TouchQueryContainerInterface
     */
    private $touchQueryContainer;

    /**
     * @var Locator
     */
    private $locator;
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();

        $this->glossaryFacade = new GlossaryFacade(
            new \SprykerEngine\Zed\Kernel\Business\Factory('Glossary'),
            $this->locator
        );
        $this->localeFacade = new LocaleFacade(new \SprykerEngine\Zed\Kernel\Business\Factory('Locale'), $this->locator);
        $this->glossaryQueryContainer = new GlossaryQueryContainer(new Factory('Glossary'), $this->locator);
        $this->touchQueryContainer = new TouchQueryContainer(new Factory('Touch'), $this->locator);
    }

    /**
     * @group Glossary
     */
    public function testCreateKeyInsertsSomething()
    {
        $keyQuery = $this->glossaryQueryContainer->queryKeys();
        $keyCountBeforeCreation = $keyQuery->count();

        $this->glossaryFacade->createKey('ATestKey');
        $keyCountAfterCreation = $keyQuery->count();
        $this->assertTrue($keyCountAfterCreation > $keyCountBeforeCreation);
    }

    /**
     * @group Glossary
     */
    public function testUpdateKeyUpdatesSomething()
    {
        $keyQuery = $this->glossaryQueryContainer->queryKeys();
        $keyId = $this->glossaryFacade->createKey('ATestKey2');

        $this->assertEquals('ATestKey2', $keyQuery->findPk($keyId)->getKey());
        $this->glossaryFacade->updateKey('ATestKey2', 'ATestKey3');
        $this->assertEquals('ATestKey3', $keyQuery->findPk($keyId)->getKey());
    }

    /**
     * @group Glossary
     */
    public function testHasKeyReturnsRightValue()
    {
        $this->glossaryFacade->createKey('SomeNewKey');
        $this->assertTrue($this->glossaryFacade->hasKey('SomeNewKey'));
    }

    /**
     * @group Glossary
     */
    public function testDeleteKeyDeletesSomething()
    {
        $specificKeyQuery = $this->glossaryQueryContainer->queryKey('KeyToBeDeleted');

        $this->glossaryFacade->createKey('KeyToBeDeleted');
        $this->assertTrue($specificKeyQuery->findOne()->getIsActive());

        $this->glossaryFacade->deleteKey('KeyToBeDeleted');

        $this->assertFalse($specificKeyQuery->findOne()->getIsActive());
    }

    /**
     * @group Glossary
     */
    public function testSynchronizeFilesWritesToDatabase()
    {
        /*
         * This test is not yet final.
         * Current Problem:
         * It might work, but can also fail, as we know nothing about the preconditions (the database state)
         * The method tests the sync between a config file with static files and the db
         * It should not insert new records if these are already synced.
         * However, this test knows nothing about the state.
         * Possible Solution: A nice way to use another key file source (or other input) under the hood,
         * i.e. by providing an overriding implementation of the file parser for the test
         * Currently one could manually switch the concrete implementation in the glossary dependency container
         */

        $keyQuery = $this->glossaryQueryContainer->queryKeys();

        $keyCountBeforeSynchronization = $keyQuery->count();
        $this->glossaryFacade->synchronizeKeys();
        $keyCountAfterSynchronization = $keyQuery->count();

        $this->assertTrue($keyCountAfterSynchronization > $keyCountBeforeSynchronization);
    }

    /**
     * @group Glossary
     */
    public function testCreateTranslationInsertsSomething()
    {
        $translationQuery = $this->glossaryQueryContainer->queryTranslations();
        $this->glossaryFacade->createKey('AKey');
        $this->localeFacade->createLocale('Local');

        $translationCountBeforeCreation = $translationQuery->count();
        $this->glossaryFacade->createTranslation('AKey', 'Local', 'ATranslation', true);
        $translationCountAfterCreation = $translationQuery->count();

        $this->assertTrue($translationCountAfterCreation > $translationCountBeforeCreation);
    }

    /**
     * @group Glossary
     */
    public function testCreateAndTouchTranslationInsertsSomethingAndTouchesIt()
    {
        $translationQuery = $this->glossaryQueryContainer->queryTranslations();
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');
        $this->glossaryFacade->createKey('AKey2');
        $this->localeFacade->createLocale('Locaz');

        $translationCountBeforeCreation = $translationQuery->count();
        $touchCountBeforeCreation = $touchQuery->count();
        $this->glossaryFacade->createAndTouchTranslation('AKey2', 'Locaz', 'ATranslation', true);
        $translationCountAfterCreation = $translationQuery->count();
        $touchCountAfterCreation = $touchQuery->count();

        $this->assertTrue($translationCountAfterCreation > $translationCountBeforeCreation);
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @group Glossary
     */
    public function testUpdateTranslationUpdatesSomething()
    {
        $this->localeFacade->createLocale('Local');

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('AnotherKey', 'Local');
        $this->glossaryFacade->createKey('AnotherKey');
        $this->glossaryFacade->createTranslation('AnotherKey', 'Local', 'Some Translation', true);

        $this->assertEquals('Some Translation', $specificTranslationQuery->findOne()->getValue());

        $this->glossaryFacade->updateTranslation('AnotherKey', 'Local', 'Some other Translation', true);

        $this->assertEquals('Some other Translation', $specificTranslationQuery->findOne()->getValue());
    }

    /**
     * @group Glossary
     */
    public function testUpdateAndTouchTranslationUpdatesSomethingAndTouchesIt()
    {
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('AnotherKey2', 'Locaz');
        $this->localeFacade->createLocale('Locaz');
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');
        $this->glossaryFacade->createKey('AnotherKey2');
        $this->glossaryFacade->createTranslation('AnotherKey2', 'Locaz', 'Some Translation', true);

        $this->assertEquals('Some Translation', $specificTranslationQuery->findOne()->getValue());
        $touchCountBeforeCreation = $touchQuery->count();

        $this->glossaryFacade->updateAndTouchTranslation('AnotherKey2', 'Locaz', 'Some other Translation', true);
        $touchCountAfterCreation = $touchQuery->count();

        $this->assertEquals('Some other Translation', $specificTranslationQuery->findOne()->getValue());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @group Glossary
     */
    public function testDeleteTranslationDeletesSoftly()
    {
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('KeyWithTranslation', 'de_DE');
        $this->glossaryFacade->createKey('KeyWithTranslation');
        $this->glossaryFacade->createTranslation('KeyWithTranslation', 'de_DE', 'A Translation...', true);
        $this->assertTrue($specificTranslationQuery->findOne()->getIsActive());

        $this->glossaryFacade->deleteTranslation('KeyWithTranslation', 'de_DE');

        $this->assertFalse($specificTranslationQuery->findOne()->getIsActive());
    }

    /**
     * @group Glossary
     */
    public function testSaveTranslationDoesACreate()
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey');
        $localeId = $this->localeFacade->createLocale('ab_xy')->getIdLocale();
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $localeId);

        $transferTranslation = new Translation($this->locator);
        $transferTranslation->setFkGlossaryKey($keyId);
        $transferTranslation->setFkLocale($localeId);
        $transferTranslation->setValue('some Value');

        $this->assertEquals(0, $specificTranslationQuery->count());

        $transferTranslation = $this->glossaryFacade->saveTranslation($transferTranslation);

        $this->assertEquals(1, $specificTranslationQuery->count());
        $this->assertNotNull($transferTranslation->getIdGlossaryTranslation());
    }

    /**
     * @group Glossary
     */
    public function testSaveTranslationDoesAnUpdate()
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey2');
        $localeId = $this->localeFacade->createLocale('ab_yz')->getIdLocale();
        $transferTranslation = $this->glossaryFacade->createTranslation(
            'SomeNonExistentKey2',
            'ab_yz',
            'some translation'
        );
        $this->assertNotNull($transferTranslation->getIdGlossaryTranslation());

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $localeId);
        $this->assertEquals(1, $specificTranslationQuery->count());

        $transferTranslation->setValue('someOtherTranslation');

        $this->glossaryFacade->saveTranslation($transferTranslation);

        $this->assertEquals('someOtherTranslation', $specificTranslationQuery->findOne()->getValue());
    }

    /**
     * @group Glossary
     */
    public function testSaveAndTouchTranslationDoesATouchForCreation()
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey3');
        $localeId = $this->localeFacade->createLocale('ab_ef')->getIdLocale();
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $localeId);
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');

        $transferTranslation = new Translation($this->locator);
        $transferTranslation->setFkGlossaryKey($keyId);
        $transferTranslation->setFkLocale($localeId);
        $transferTranslation->setValue('some Value');

        $this->assertEquals(0, $specificTranslationQuery->count());
        $touchCountBeforeCreation = $touchQuery->count();

        $transferTranslation = $this->glossaryFacade->saveAndTouchTranslation($transferTranslation);

        $touchCountAfterCreation = $touchQuery->count();

        $this->assertEquals(1, $specificTranslationQuery->count());
        $this->assertNotNull($transferTranslation->getIdGlossaryTranslation());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @group Glossary
     */
    public function testSaveAndTouchTranslationDoesATouchForUpdate()
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey4');
        $localeId = $this->localeFacade->createLocale('ab_fg')->getIdLocale();
        $transferTranslation = $this->glossaryFacade->createTranslation('SomeNonExistentKey4', 'ab_fg', 'some Value', true);

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $localeId);
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');

        $this->assertEquals(1, $specificTranslationQuery->count());
        $touchCountBeforeCreation = $touchQuery->count();

        $transferTranslation->setValue('setSomeOtherTranslation');
        $this->glossaryFacade->saveAndTouchTranslation($transferTranslation);

        $touchCountAfterCreation = $touchQuery->count();

        $this->assertEquals('setSomeOtherTranslation', $specificTranslationQuery->findOne()->getValue());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @group Glossary
     */
    public function testCreatingTranslationForCurrentLocaleInsertsSomething()
    {
        $translationQuery = $this->glossaryQueryContainer->queryTranslations();
        $this->glossaryFacade->createKey('SomeNonExistingKey5');

        $translationCountBeforeCreation = $translationQuery->count();
        $translation = $this->glossaryFacade->createTranslationForCurrentLocale('SomeNonExistingKey5', 'WhateverTranslation');
        $translationCountAfterCreation = $translationQuery->count();

        $this->assertTrue($translationCountAfterCreation > $translationCountBeforeCreation);

        $this->assertNotNull($translation->getIdGlossaryTranslation());
    }
}

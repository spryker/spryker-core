<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Glossary;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Business\Factory as BusinessFactory;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\Factory as PersistenceFactory;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Generated\Shared\Transfer\TranslationTransfer;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface;
use SprykerFeature\Zed\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Glossary
 * @group GlossaryTest
 */
class GlossaryTest extends Test
{
    /**
     * @var GlossaryFacade
     */
    private $glossaryFacade;

    /**
     * @var GlossaryToLocaleInterface
     */
    private $localeFacade;

    /**
     * @var GlossaryToTouchInterface
     */
    private $touchFacade;


    /**
     * @var GlossaryQueryContainerInterface
     */
    private $glossaryQueryContainer;

    /**
     * @var TouchQueryContainerInterface
     */
    private $touchQueryContainer;

    /**
     * @var AutoCompletion
     */
    private $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();

        $this->localeFacade = new Mock\LocaleFacade(new BusinessFactory('Locale'), $this->locator);
        $this->touchFacade = new Mock\TouchFacade(new BusinessFactory('Touch'), $this->locator);
        $this->glossaryQueryContainer = new Mock\GlossaryQueryContainer(new PersistenceFactory('Glossary'), $this->locator);
        $this->touchQueryContainer = new Mock\TouchQueryContainer(new PersistenceFactory('Touch'), $this->locator);

        $this->buildGlossaryFacade();
    }

    public function testCreateKeyInsertsSomething()
    {
        $keyQuery = $this->glossaryQueryContainer->queryKeys();
        $keyCountBeforeCreation = $keyQuery->count();

        $this->glossaryFacade->createKey('ATestKey');
        $keyCountAfterCreation = $keyQuery->count();
        $this->assertTrue($keyCountAfterCreation > $keyCountBeforeCreation);
    }

    public function testUpdateKeyUpdatesSomething()
    {
        $keyQuery = $this->glossaryQueryContainer->queryKeys();
        $keyId = $this->glossaryFacade->createKey('ATestKey2');

        $this->assertEquals('ATestKey2', $keyQuery->findPk($keyId)->getKey());
        $this->glossaryFacade->updateKey('ATestKey2', 'ATestKey3');
        $this->assertEquals('ATestKey3', $keyQuery->findPk($keyId)->getKey());
    }

    public function testHasKeyReturnsRightValue()
    {
        $this->glossaryFacade->createKey('SomeNewKey');
        $this->assertTrue($this->glossaryFacade->hasKey('SomeNewKey'));
    }

    public function testDeleteKeyDeletesSomething()
    {
        $specificKeyQuery = $this->glossaryQueryContainer->queryKey('KeyToBeDeleted');

        $this->glossaryFacade->createKey('KeyToBeDeleted');
        $this->assertTrue($specificKeyQuery->findOne()->getIsActive());

        $this->glossaryFacade->deleteKey('KeyToBeDeleted');

        $this->assertFalse($specificKeyQuery->findOne()->getIsActive());
    }

    public function testCreateTranslationInsertsSomething()
    {
        $translationQuery = $this->glossaryQueryContainer->queryTranslations();
        $this->glossaryFacade->createKey('AKey');
        $locale = $this->localeFacade->createLocale('Local');

        $translationCountBeforeCreation = $translationQuery->count();
        $this->glossaryFacade->createTranslation('AKey', $locale, 'ATranslation', true);
        $translationCountAfterCreation = $translationQuery->count();

        $this->assertTrue($translationCountAfterCreation > $translationCountBeforeCreation);
    }

    public function testCreateAndTouchTranslationInsertsSomethingAndTouchesIt()
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

    public function testUpdateTranslationUpdatesSomething()
    {
        $locale = $this->localeFacade->createLocale('Local');

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('AnotherKey', $locale->getLocaleName());
        $this->glossaryFacade->createKey('AnotherKey');
        $this->glossaryFacade->createTranslation('AnotherKey', $locale, 'Some Translation', true);

        $this->assertEquals('Some Translation', $specificTranslationQuery->findOne()->getValue());

        $this->glossaryFacade->updateTranslation('AnotherKey', $locale, 'Some other Translation', true);

        $this->assertEquals('Some other Translation', $specificTranslationQuery->findOne()->getValue());
    }

    public function testUpdateAndTouchTranslationUpdatesSomethingAndTouchesIt()
    {
        $locale = $this->localeFacade->createLocale('Locaz');
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('AnotherKey2', $locale->getLocaleName());
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');
        $this->glossaryFacade->createKey('AnotherKey2');
        $this->glossaryFacade->createTranslation('AnotherKey2', $locale, 'Some Translation', true);

        $this->assertEquals('Some Translation', $specificTranslationQuery->findOne()->getValue());
        $touchCountBeforeCreation = $touchQuery->count();

        $this->glossaryFacade->updateAndTouchTranslation('AnotherKey2', $locale, 'Some other Translation', true);
        $touchCountAfterCreation = $touchQuery->count();

        $this->assertEquals('Some other Translation', $specificTranslationQuery->findOne()->getValue());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    public function testDeleteTranslationDeletesSoftly()
    {
        $locale = $this->localeFacade->createLocale('yx_qw');
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByNames('KeyWithTranslation', $locale->getLocaleName());
        $this->glossaryFacade->createKey('KeyWithTranslation');
        $this->glossaryFacade->createTranslation('KeyWithTranslation', $locale, 'A Translation...', true);
        $this->assertTrue($specificTranslationQuery->findOne()->getIsActive());

        $this->glossaryFacade->deleteTranslation('KeyWithTranslation', $locale);

        $this->assertFalse($specificTranslationQuery->findOne()->getIsActive());
    }

    public function testSaveTranslationDoesACreate()
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey');
        $locale = $this->localeFacade->createLocale('ab_xy');
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $locale->getIdLocale());

        $transferTranslation = new TranslationTransfer();
        $transferTranslation->setFkGlossaryKey($keyId);
        $transferTranslation->setFkLocale($locale->getIdLocale());
        $transferTranslation->setValue('some Value');

        $this->assertEquals(0, $specificTranslationQuery->count());

        $transferTranslation = $this->glossaryFacade->saveTranslation($transferTranslation);

        $this->assertEquals(1, $specificTranslationQuery->count());
        $this->assertNotNull($transferTranslation->getIdGlossaryTranslation());
    }

    public function testSaveTranslationDoesAnUpdate()
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
        $this->assertEquals(1, $specificTranslationQuery->count());

        $transferTranslation->setValue('someOtherTranslation');

        $this->glossaryFacade->saveTranslation($transferTranslation);

        $this->assertEquals('someOtherTranslation', $specificTranslationQuery->findOne()->getValue());
    }

    public function testSaveAndTouchTranslationDoesATouchForCreation()
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey3');
        $localeId = $this->localeFacade->createLocale('ab_ef')->getIdLocale();
        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $localeId);
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');

        $transferTranslation = new TranslationTransfer($this->locator);
        $transferTranslation->setFkGlossaryKey($keyId);
        $transferTranslation->setFkLocale($localeId);
        $transferTranslation->setValue('some Value');
        $transferTranslation->setIsActive(true);

        $this->assertEquals(0, $specificTranslationQuery->count());
        $touchCountBeforeCreation = $touchQuery->count();

        $transferTranslation = $this->glossaryFacade->saveAndTouchTranslation($transferTranslation);

        $touchCountAfterCreation = $touchQuery->count();

        $this->assertEquals(1, $specificTranslationQuery->count());
        $this->assertNotNull($transferTranslation->getIdGlossaryTranslation());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    public function testSaveAndTouchTranslationDoesATouchForUpdate()
    {
        $keyId = $this->glossaryFacade->createKey('SomeNonExistentKey4');
        $locale = $this->localeFacade->createLocale('ab_fg');
        $transferTranslation = $this->glossaryFacade->createTranslation('SomeNonExistentKey4', $locale, 'some Value', true);

        $specificTranslationQuery = $this->glossaryQueryContainer->queryTranslationByIds($keyId, $locale->getIdLocale());
        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('translation');

        $this->assertEquals(1, $specificTranslationQuery->count());
        $touchCountBeforeCreation = $touchQuery->count();

        $transferTranslation->setValue('setSomeOtherTranslation');
        $this->glossaryFacade->saveAndTouchTranslation($transferTranslation);

        $touchCountAfterCreation = $touchQuery->count();

        $this->assertEquals('setSomeOtherTranslation', $specificTranslationQuery->findOne()->getValue());
        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

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

    protected function buildGlossaryFacade()
    {
        $this->glossaryFacade = new GlossaryFacade(
            new BusinessFactory('Glossary'),
            $this->locator // DEPRECATED
        );

        $container = new Container();

        $container[GlossaryDependencyProvider::FACADE_TOUCH] = function (Container $container) {
            return $this->touchFacade ;
        };

        $container[GlossaryDependencyProvider::FACADE_LOCALE] = function (Container $container) {
            return $this->localeFacade;
        };

        $this->glossaryFacade->setExternalDependencies($container);

        $this->glossaryFacade->setOwnQueryContainer($this->glossaryQueryContainer);
    }
}

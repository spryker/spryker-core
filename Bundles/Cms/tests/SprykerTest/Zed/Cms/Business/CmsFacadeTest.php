<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\Cms\Business\Page\LocaleCmsPageDataExpander;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\CmsExtension\Dependency\Plugin\CmsPageDataExpanderPluginInterface;
use Spryker\Zed\Glossary\Business\GlossaryBusinessFactory;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Glossary\GlossaryDependencyProvider;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Facade
 * @group CmsFacadeTest
 * Add your own group annotations below this line
 */
class CmsFacadeTest extends Unit
{
    /**
     * @var \Spryker\Zed\Cms\Business\CmsFacade
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Glossary\Business\GlossaryFacade
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\Url\Business\UrlFacade
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainer
     */
    protected $touchQueryContainer;

    /**
     * @var \SprykerTest\Zed\Cms\CmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cmsFacade = new CmsFacade();
        $this->urlFacade = new UrlFacade();
        $this->localeFacade = new LocaleFacade();

        $this->cmsQueryContainer = new CmsQueryContainer();
        $this->glossaryQueryContainer = new GlossaryQueryContainer();
        $this->touchQueryContainer = new TouchQueryContainer();

        $this->buildGlossaryFacade();
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testCreateTemplateInsertsAndReturnsSomething()
    {
        $templateQuery = $this->cmsQueryContainer->queryTemplates();

        $templateCountBeforeCreation = $templateQuery->count();
        $newTemplate = $this->cmsFacade->createTemplate('ATemplateName', 'ATemplatePath');
        $templateCountAfterCreation = $templateQuery->count();

        $this->assertTrue($templateCountAfterCreation > $templateCountBeforeCreation);

        $this->assertNotNull($newTemplate->getIdCmsTemplate());
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testSavePageInsertsAndReturnsSomethingOnCreate()
    {
        $pageQuery = $this->cmsQueryContainer->queryPages();
        $this->localeFacade->createLocale('ABCDE');

        $template = $this->cmsFacade->createTemplate('AUsedTemplateName', 'AUsedTemplatePath');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());
        $page->setIsActive(true);

        $pageCountBeforeCreation = $pageQuery->count();
        $page = $this->cmsFacade->savePage($page);
        $pageCountAfterCreation = $pageQuery->count();

        $this->assertTrue($pageCountAfterCreation > $pageCountBeforeCreation);

        $this->assertNotNull($page->getIdCmsPage());
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testSavePageWithNewTemplateMustSaveFkTemplateInPage()
    {
        $template1 = $this->cmsFacade->createTemplate('AnotherUsedTemplateName', 'AnotherUsedTemplatePath');
        $template2 = $this->cmsFacade->createTemplate('YetAnotherUsedTemplateName', 'YetAnotherUsedTemplatePath');

        $pageTransfer = new PageTransfer();
        $pageTransfer->setUrl($this->getUrlTransfer());
        $pageTransfer->setFkTemplate($template1->getIdCmsTemplate());
        $pageTransfer->setIsActive(true);

        $pageTransfer = $this->cmsFacade->savePage($pageTransfer);

        $pageEntity = $this->cmsQueryContainer->queryPageById($pageTransfer->getIdCmsPage())
            ->findOne();
        $this->assertEquals($template1->getIdCmsTemplate(), $pageEntity->getFkTemplate());

        $pageTransfer->setFkTemplate($template2->getIdCmsTemplate());
        $this->cmsFacade->savePage($pageTransfer);

        $pageEntity = $this->cmsQueryContainer->queryPageById($pageTransfer->getIdCmsPage())
            ->findOne();

        $this->assertEquals($template2->getIdCmsTemplate(), $pageEntity->getFkTemplate());
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testSaveTemplateInsertsAndReturnsSomethingOnCreate()
    {
        $template = new CmsTemplateTransfer();
        $template->setTemplateName('WhatARandomName');
        $template->setTemplatePath('WhatARandomPath');

        $templateQuery = $this->cmsQueryContainer->queryTemplates();

        $templateCountBeforeCreation = $templateQuery->count();
        $template = $this->cmsFacade->saveTemplate($template);
        $templateCountAfterCreation = $templateQuery->count();

        $this->assertTrue($templateCountAfterCreation > $templateCountBeforeCreation);

        $this->assertNotNull($template->getIdCmsTemplate());
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testSaveTemplateUpdatesSomething()
    {
        $template = new CmsTemplateTransfer();
        $template->setTemplateName('WhatARandomName');
        $template->setTemplatePath('WhatARandomPath2');
        $template = $this->cmsFacade->saveTemplate($template);

        $templateQuery = $this->cmsQueryContainer->queryTemplateById($template->getIdCmsTemplate());

        $this->assertEquals('WhatARandomPath2', $templateQuery->findOne()
            ->getTemplatePath());

        $template->setTemplatePath('WhatAnotherRandomPath2');
        $this->cmsFacade->saveTemplate($template);

        $this->assertEquals('WhatAnotherRandomPath2', $templateQuery->findOne()
            ->getTemplatePath());
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testSavePageKeyMappingInsertsAndReturnsSomethingOnCreate()
    {
        $pageKeyMappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMappings();

        $glossaryKeyId = $this->glossaryFacade->createKey('AHopefullyNotYetExistingKey');
        $template = $this->cmsFacade->createTemplate('ANotExistingTemplateName', 'ANotYetExistingTemplatePath');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());
        $page->setIsActive(true);

        $page = $this->cmsFacade->savePage($page);

        $pageKeyMapping = new PageKeyMappingTransfer();
        $pageKeyMapping->setFkGlossaryKey($glossaryKeyId);
        $pageKeyMapping->setFkPage($page->getIdCmsPage());
        $pageKeyMapping->setPlaceholder('SomePlaceholderName');

        $mappingCountBeforeCreation = $pageKeyMappingQuery->count();
        $pageKeyMapping = $this->cmsFacade->savePageKeyMapping($pageKeyMapping);
        $mappingCountAfterCreation = $pageKeyMappingQuery->count();

        $this->assertTrue($mappingCountAfterCreation > $mappingCountBeforeCreation);

        $this->assertNotNull($pageKeyMapping->getIdCmsGlossaryKeyMapping());
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testSavePageKeyMappingUpdatesSomething()
    {
        $glossaryKeyId1 = $this->glossaryFacade->createKey('AHopefullyNotYetExistingKey2');
        $glossaryKeyId2 = $this->glossaryFacade->createKey('AHopefullyNotYetExistingKey3');
        $template = $this->cmsFacade->createTemplate('ANotExistingTemplateName2', 'ANotYetExistingTemplatePath2');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());
        $page->setIsActive(true);

        $page = $this->cmsFacade->savePage($page);

        $pageKeyMapping = new PageKeyMappingTransfer();
        $pageKeyMapping->setFkGlossaryKey($glossaryKeyId1);
        $pageKeyMapping->setFkPage($page->getIdCmsPage());
        $pageKeyMapping->setPlaceholder('SomePlaceholderName');

        $pageKeyMapping = $this->cmsFacade->savePageKeyMapping($pageKeyMapping);

        $pageKeyMappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMappingById($pageKeyMapping->getIdCmsGlossaryKeyMapping());

        $this->assertEquals($glossaryKeyId1, $pageKeyMappingQuery->findOne()
            ->getFkGlossaryKey());

        $pageKeyMapping->setFkGlossaryKey($glossaryKeyId2);
        $this->cmsFacade->savePageKeyMapping($pageKeyMapping);

        $this->assertEquals($glossaryKeyId2, $pageKeyMappingQuery->findOne()
            ->getFkGlossaryKey());
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testAddPlaceholderTextInsertsAndReturnsSomething()
    {
        $keyQuery = $this->glossaryQueryContainer->queryKeys();
        $pageMappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMappings();

        $template = $this->cmsFacade->createTemplate('APlaceholderTemplate', 'APlaceholderTemplatePath');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());
        $page->setIsActive(true);

        $page = $this->cmsFacade->savePage($page);

        $keyCountBeforeCreation = $keyQuery->count();
        $mappingCountBeforeCreation = $pageMappingQuery->count();

        $mapping = $this->cmsFacade->addPlaceholderText($page, 'Placeholder1', 'Some Translation');

        $keyCountAfterCreation = $keyQuery->count();
        $mappingCountAfterCreation = $pageMappingQuery->count();

        $this->assertTrue($keyCountAfterCreation > $keyCountBeforeCreation);
        $this->assertTrue($mappingCountAfterCreation > $mappingCountBeforeCreation);

        $this->assertNotNull($mapping->getIdCmsGlossaryKeyMapping());
    }

    /**
     * @group Cms
     *
     * @return void
     */
    public function testTranslatePlaceholder()
    {
        $template = $this->cmsFacade->createTemplate('APlaceholderTemplate2', 'APlaceholderTemplatePath2');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());
        $page->setIsActive(true);

        $page = $this->cmsFacade->savePage($page);
        $this->cmsFacade->addPlaceholderText($page, 'Placeholder1', 'A Placeholder Translation');

        $translation = $this->cmsFacade->translatePlaceholder($page->getIdCmsPage(), 'Placeholder1');
        $this->assertEquals('A Placeholder Translation', $translation);
    }

    /**
     * @return void
     */
    public function testCreatePageAndTouchForCustomLocale()
    {
        $localeTransfer = $this->localeFacade->createLocale('ABCDE');
        $template = $this->cmsFacade->createTemplate('APlaceholderTemplate2', 'APlaceholderTemplatePath2');

        $pageTransfer = new PageTransfer();
        $pageTransfer->setFkTemplate($template->getIdCmsTemplate());
        $pageTransfer->setIsActive(true);

        $pageTransfer = $this->cmsFacade->savePage($pageTransfer);
        $this->cmsFacade->addPlaceholderText($pageTransfer, 'Placeholder1', 'A Placeholder Translation', $localeTransfer);

        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('page');

        $touchCountBeforeCreation = $touchQuery->count();
        $this->cmsFacade->touchPageActive($pageTransfer, $localeTransfer);
        $touchCountAfterCreation = $touchQuery->count();

        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testCreatePageAndTouchForCurrentLocale()
    {
        $template = $this->cmsFacade->createTemplate('APlaceholderTemplate2', 'APlaceholderTemplatePath2');

        $pageTransfer = new PageTransfer();
        $pageTransfer->setFkTemplate($template->getIdCmsTemplate());
        $pageTransfer->setIsActive(true);

        $pageTransfer = $this->cmsFacade->savePage($pageTransfer);
        $this->cmsFacade->addPlaceholderText($pageTransfer, 'Placeholder1', 'A Placeholder Translation');

        $touchQuery = $this->touchQueryContainer->queryTouchListByItemType('page');

        $touchCountBeforeCreation = $touchQuery->count();
        $this->cmsFacade->touchPageActive($pageTransfer);
        $touchCountAfterCreation = $touchQuery->count();

        $this->assertTrue($touchCountAfterCreation > $touchCountBeforeCreation);
    }

    /**
     * @return void
     */
    public function testCalculateFlattenedLocaleCmsPageDataAppliesPreConfiguredCmsPageDataExpanderPlugins()
    {
        // Assign
        $input = new LocaleCmsPageDataTransfer();
        $expanderPlugin = $this->createMock(CmsPageDataExpanderPluginInterface::class);
        $this->tester->setDependency(CmsDependencyProvider::PLUGINS_CMS_PAGE_DATA_EXPANDER, [$expanderPlugin]);

        // Assert
        $expanderPlugin->expects($this->once())->method('expand')->willReturn([]);

        // Act
        $this->cmsFacade->calculateFlattenedLocaleCmsPageData($input, new LocaleTransfer());
    }

    /**
     * @return void
     */
    public function testCalculateFlattenedLocaleCmsPageDataRetrievesFlattenedArray()
    {
        // Assign
        $expectedResult = [
            LocaleCmsPageDataExpander::PARAM_URL,
            LocaleCmsPageDataExpander::PARAM_VALID_FROM,
            LocaleCmsPageDataExpander::PARAM_VALID_TO,
            LocaleCmsPageDataExpander::PARAM_IS_ACTIVE,
            LocaleCmsPageDataExpander::PARAM_ID,
            LocaleCmsPageDataExpander::PARAM_TEMPLATE,
            LocaleCmsPageDataExpander::PARAM_PLACEHOLDERS,
            LocaleCmsPageDataExpander::PARAM_NAME,
            LocaleCmsPageDataExpander::PARAM_META_TITLE,
            LocaleCmsPageDataExpander::PARAM_META_KEYWORDS,
            LocaleCmsPageDataExpander::PARAM_META_DESCRIPTION,
        ];
        $input = new LocaleCmsPageDataTransfer();
        $this->tester->setDependency(CmsDependencyProvider::PLUGINS_CMS_PAGE_DATA_EXPANDER, []);

        // Act
        $actualResult = $this->cmsFacade->calculateFlattenedLocaleCmsPageData($input, new LocaleTransfer());

        // Assert
        $this->assertEquals($expectedResult, array_keys($actualResult));
    }

    /**
     * @return void
     */
    public function testExtractCmsVersionDataTransferReturnsCmsVersionDataTransfer()
    {
        // Assign
        $input = '{}';
        $expectedResultClass = CmsVersionDataTransfer::class;

        // Act
        $actualResult = $this->cmsFacade->extractCmsVersionDataTransfer($input);

        // Assert
        $this->assertEquals($expectedResultClass, get_class($actualResult));
    }

    /**
     * @return void
     */
    public function testExtractCmsVersionDataTransferPopulatesCmsVersionDataTransfer()
    {
        // Assign
        $expectedResult = "dummyTestValue";
        $input = sprintf('{"cmsPage":{"templateName": "%s"}}', $expectedResult);

        // Act
        $actualResult = $this->cmsFacade->extractCmsVersionDataTransfer($input);

        // Assert
        $this->assertEquals($expectedResult, $actualResult->getCmsPage()->getTemplateName());
    }

    /**
     * @return void
     */
    public function testExtractLocaleCmsPageDataTransferReturnsLocaleCmsPageDataTransfer()
    {
        // Assign
        $input = (new CmsVersionDataTransfer())
            ->setCmsGlossary(new CmsGlossaryTransfer())
            ->setCmsPage(new CmsPageTransfer())
            ->setCmsTemplate(new CmsTemplateTransfer());
        $expectedResultClass = LocaleCmsPageDataTransfer::class;

        // Act
        $actualResult = $this->cmsFacade->extractLocaleCmsPageDataTransfer($input, new LocaleTransfer());

        // Assert
        $this->assertEquals($expectedResultClass, get_class($actualResult));
    }

    /**
     * @return void
     */
    protected function buildGlossaryFacade()
    {
        $this->glossaryFacade = new GlossaryFacade();
        $container = new Container();

        $container[GlossaryDependencyProvider::FACADE_LOCALE] = function (Container $container) {
            return $this->localeFacade;
        };

        $factory = new GlossaryBusinessFactory();
        $factory->setContainer($container);

        $this->glossaryFacade->setFactory($factory);
    }

    /**
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function getUrlTransfer()
    {
        $urlTransfer = new UrlTransfer();
        $localeEntity = (new LocaleQueryContainer())->queryLocales()->findOne();

        $urlTransfer
            ->setFkLocale($localeEntity->getIdLocale())
            ->setUrl('/url');

        return $urlTransfer;
    }
}

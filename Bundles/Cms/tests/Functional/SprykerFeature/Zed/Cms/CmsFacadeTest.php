<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Cms;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainer;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainer;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use SprykerFeature\Zed\Url\Business\UrlFacade;

class CmsFacadeTest extends Test
{

    /**
     * @var CmsFacade
     */
    protected $cmsFacade;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var GlossaryFacade
     */
    protected $glossaryFacade;

    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var GlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
        $this->cmsFacade = new CmsFacade(new Factory('Cms'), $this->locator);
        $this->urlFacade = new UrlFacade(new Factory('Url'), $this->locator);

        $this->localeFacade = new LocaleFacade(new Factory('Locale'), $this->locator);
        $this->cmsQueryContainer = new CmsQueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Cms'), $this->locator);
        $this->glossaryQueryContainer = new GlossaryQueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Glossary'), $this->locator);

        $this->buildGlossaryFacade();
    }

    /**
     * @group Cms
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
     */
    public function testSavePageInsertsAndReturnsSomethingOnCreate()
    {
        $pageQuery = $this->cmsQueryContainer->queryPages();
        $this->localeFacade->createLocale('ABCDE');

        $template = $this->cmsFacade->createTemplate('AUsedTemplateName', 'AUsedTemplatePath');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());

        $pageCountBeforeCreation = $pageQuery->count();
        $page = $this->cmsFacade->savePage($page);
        $pageCountAfterCreation = $pageQuery->count();

        $this->assertTrue($pageCountAfterCreation > $pageCountBeforeCreation);

        $this->assertNotNull($page->getIdCmsPage());
    }

    /**
     * @group Cms
     */
    public function testSavePageUpdatesSomething()
    {
        $template1 = $this->cmsFacade->createTemplate('AnotherUsedTemplateName', 'AnotherUsedTemplatePath');
        $template2 = $this->cmsFacade->createTemplate('YetAnotherUsedTemplateName', 'YetAnotherUsedTemplatePath');

        $page = new PageTransfer();
        $page->setFkTemplate($template1->getIdCmsTemplate());

        $page = $this->cmsFacade->savePage($page);

        $pageEntity = $this->cmsQueryContainer->queryPageById($page->getIdCmsPage())->findOne();
        $this->assertEquals($template1->getIdCmsTemplate(), $pageEntity->getFkTemplate());

        $page->setFkTemplate($template2->getIdCmsTemplate());
        $this->cmsFacade->savePage($page);

        $this->assertEquals($template2->getIdCmsTemplate(), $pageEntity->getFkTemplate());
    }

    /**
     * @group Cms
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
     */
    public function testSaveTemplateUpdatesSomething()
    {
        $template = new CmsTemplateTransfer();
        $template->setTemplateName('WhatARandomName');
        $template->setTemplatePath('WhatARandomPath2');
        $template = $this->cmsFacade->saveTemplate($template);

        $templateQuery = $this->cmsQueryContainer->queryTemplateById($template->getIdCmsTemplate());

        $this->assertEquals('WhatARandomPath2', $templateQuery->findOne()->getTemplatePath());

        $template->setTemplatePath('WhatAnotherRandomPath2');
        $this->cmsFacade->saveTemplate($template);

        $this->assertEquals('WhatAnotherRandomPath2', $templateQuery->findOne()->getTemplatePath());
    }

    /**
     * @group Cms
     */
    public function testSavePageKeyMappingInsertsAndReturnsSomethingOnCreate()
    {
        $pageKeyMappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMappings();

        $glossaryKeyId = $this->glossaryFacade->createKey('AHopefullyNotYetExistingKey');
        $template = $this->cmsFacade->createTemplate('ANotExistingTemplateName', 'ANotYetExistingTemplatePath');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());
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
     */
    public function testSavePageKeyMappingUpdatesSomething()
    {
        $glossaryKeyId1 = $this->glossaryFacade->createKey('AHopefullyNotYetExistingKey2');
        $glossaryKeyId2 = $this->glossaryFacade->createKey('AHopefullyNotYetExistingKey3');
        $template = $this->cmsFacade->createTemplate('ANotExistingTemplateName2', 'ANotYetExistingTemplatePath2');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());
        $page = $this->cmsFacade->savePage($page);

        $pageKeyMapping = new PageKeyMappingTransfer();
        $pageKeyMapping->setFkGlossaryKey($glossaryKeyId1);
        $pageKeyMapping->setFkPage($page->getIdCmsPage());
        $pageKeyMapping->setPlaceholder('SomePlaceholderName');

        $pageKeyMapping = $this->cmsFacade->savePageKeyMapping($pageKeyMapping);

        $pageKeyMappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMappingById($pageKeyMapping->getIdCmsGlossaryKeyMapping());

        $this->assertEquals($glossaryKeyId1, $pageKeyMappingQuery->findOne()->getFkGlossaryKey());

        $pageKeyMapping->setFkGlossaryKey($glossaryKeyId2);
        $this->cmsFacade->savePageKeyMapping($pageKeyMapping);

        $this->assertEquals($glossaryKeyId2, $pageKeyMappingQuery->findOne()->getFkGlossaryKey());
    }

    /**
     * @group Cms
     */
    public function testAddPlaceholderTextInsertsAndReturnsSomething()
    {
        $keyQuery = $this->glossaryQueryContainer->queryKeys();
        $pageMappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMappings();

        $template = $this->cmsFacade->createTemplate('APlaceholderTemplate', 'APlaceholderTemplatePath');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());

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
     */
    public function testTranslatePlaceholder()
    {
        $template = $this->cmsFacade->createTemplate('APlaceholderTemplate2', 'APlaceholderTemplatePath2');

        $page = new PageTransfer();
        $page->setFkTemplate($template->getIdCmsTemplate());

        $page = $this->cmsFacade->savePage($page);
        $this->cmsFacade->addPlaceholderText($page, 'Placeholder1', 'A Placeholder Translation');

        $translation = $this->cmsFacade->translatePlaceholder($page->getIdCmsPage(), 'Placeholder1');
        $this->assertEquals('A Placeholder Translation', $translation);
    }

    protected function buildGlossaryFacade()
    {
        $this->glossaryFacade = new GlossaryFacade(new Factory('Glossary'), $this->locator);

        $container = new Container();

        $container[GlossaryDependencyProvider::FACADE_LOCALE] = function (Container $container) {
            return $this->localeFacade;
        };

        $this->glossaryFacade->setExternalDependencies($container);

        $this->glossaryFacade->setOwnQueryContainer($this->glossaryQueryContainer);
    }

}

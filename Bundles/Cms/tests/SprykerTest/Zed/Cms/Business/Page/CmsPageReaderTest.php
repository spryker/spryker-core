<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business\Page;

use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Orm\Zed\Cms\Persistence\SpyCmsTemplate;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Cms\Business\Page\CmsPageMapperInterface;
use Spryker\Zed\Cms\Business\Page\CmsPageReader;
use Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerTest\Zed\Cms\Business\CmsMocks;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Page
 * @group CmsPageReaderTest
 * Add your own group annotations below this line
 */
class CmsPageReaderTest extends CmsMocks
{
    /**
     * @return void
     */
    public function testGetCmsPageByIdShouldReturnMappedTransferObjectFromPersistence()
    {
        $cmsPageReaderMock = $this->createCmsPageReaderMock();

        $cmsPageEntity = $this->buildCmsPageEntity();

        $cmsPageReaderMock->expects($this->once())
            ->method('findCmsPageEntity')
            ->willReturn($cmsPageEntity);

        $cmsPageTransfer = $cmsPageReaderMock->findCmsPageById(1);

        $this->assertEquals($cmsPageEntity->getIdCmsPage(), $cmsPageTransfer->getFkPage());
        $this->assertCount(2, $cmsPageTransfer->getPageAttributes());
    }

    /**
     * @return void
     */
    public function testGetCmsPageByIdWhenPageNotFoundShouldReturnNull()
    {
        $cmsPageReaderMock = $this->createCmsPageReaderMock();

        $cmsPageReaderMock->expects($this->once())
            ->method('findCmsPageEntity')
            ->willReturn(null);

        $cmsPageTransfer = $cmsPageReaderMock->findCmsPageById(1);

        $this->assertNull($cmsPageTransfer);
    }

    /**
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageMapperInterface|null $cmsPageMapperMock
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface|null $cmsQueryContainerMock
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface|null $localeFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Page\CmsPageReader
     */
    protected function createCmsPageReaderMock(
        ?CmsPageMapperInterface $cmsPageMapperMock = null,
        ?CmsQueryContainerInterface $cmsQueryContainerMock = null,
        ?CmsToLocaleInterface $localeFacadeMock = null
    ) {
        if ($cmsPageMapperMock === null) {
            $cmsPageMapperMock = $this->createCmsPageMapperMock();
        }

        if ($cmsQueryContainerMock === null) {
            $cmsQueryContainerMock = $this->createCmsQueryContainerMock();
        }

        if ($localeFacadeMock === null) {
            $localeFacadeMock = $this->createLocaleMock();
        }

        $localeFacadeMock->method('getAvailableLocales')
            ->willReturn($this->getAvailableLocales());

        return $this->getMockBuilder(CmsPageReader::class)
            ->setMethods(['findCmsPageEntity'])
            ->setConstructorArgs([$cmsQueryContainerMock, $cmsPageMapperMock, $localeFacadeMock])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface
     */
    protected function createCmsUrlBuilderMock()
    {
        return $this->getMockBuilder(CmsPageUrlBuilderInterface::class)
            ->getMock();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function buildCmsPageEntity()
    {
        $cmsPageEntity = $this->createCmsPageEntityMock();
        $cmsPageEntity->setIdCmsPage(1);

        $cmsTemplateEntity = new SpyCmsTemplate();
        $cmsTemplateEntity->setTemplateName('template name');
        $cmsPageEntity->setCmsTemplate($cmsTemplateEntity);

        $urlEntity = new SpyUrl();
        $urlEntity->setUrl('/en/test');
        $cmsPageEntity->addSpyUrl($urlEntity);

        $urlEntity = new SpyUrl();
        $urlEntity->setUrl('/de/test');
        $cmsPageEntity->addSpyUrl($urlEntity);

        $cmsLocalizedPageAttributesEntity = new SpyCmsPageLocalizedAttributes();
        $localeEntity = new SpyLocale();
        $cmsLocalizedPageAttributesEntity->setLocale($localeEntity);
        $cmsPageEntity->addSpyCmsPageLocalizedAttributes($cmsLocalizedPageAttributesEntity);

        $cmsLocalizedPageAttributesEntity = new SpyCmsPageLocalizedAttributes();
        $localeEntity = new SpyLocale();
        $cmsLocalizedPageAttributesEntity->setLocale($localeEntity);
        $cmsPageEntity->addSpyCmsPageLocalizedAttributes($cmsLocalizedPageAttributesEntity);

        return $cmsPageEntity;
    }

    /**
     * @return string[]
     */
    protected function getAvailableLocales(): array
    {
        return [
            1 => 'en_US',
            2 => 'de_DE',
        ];
    }
}

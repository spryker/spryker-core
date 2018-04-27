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
use Spryker\Zed\Cms\Business\Page\CmsPageReader;
use Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerTest\Zed\Cms\Business\CmsMocks;

/**
 * Auto-generated group annotations
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
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface|null $cmsUrlBuilderMock
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface|null $cmsQueryContainerMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Business\Page\CmsPageReader
     */
    protected function createCmsPageReaderMock(
        ?CmsPageUrlBuilderInterface $cmsUrlBuilderMock = null,
        ?CmsQueryContainerInterface $cmsQueryContainerMock = null
    ) {

        if ($cmsQueryContainerMock === null) {
            $cmsQueryContainerMock = $this->createCmsQueryContainerMock();
        }

        if ($cmsUrlBuilderMock === null) {
            $cmsUrlBuilderMock = $this->createCmsUrlBuilderMock();
        }

        return $this->getMockBuilder(CmsPageReader::class)
            ->setMethods(['findCmsPageEntity'])
            ->setConstructorArgs([$cmsQueryContainerMock, $cmsUrlBuilderMock])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface
     */
    protected function createCmsUrlBuilderMock()
    {
        return $this->getMockBuilder(CmsPageUrlBuilderInterface::class)
            ->getMock();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|\PHPUnit_Framework_MockObject_MockObject
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
}

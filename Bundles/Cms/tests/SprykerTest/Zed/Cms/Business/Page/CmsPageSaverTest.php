<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface;
use Spryker\Zed\Cms\Business\Page\CmsPageSaver;
use Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface;
use Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationWriterInterface;
use Spryker\Zed\Cms\Business\Template\TemplateManagerInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\Url\Business\UrlFacadeInterface;
use SprykerTest\Zed\Cms\Business\CmsMocks;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Page
 * @group CmsPageSaverTest
 * Add your own group annotations below this line
 */
class CmsPageSaverTest extends CmsMocks
{
    /**
     * @return void
     */
    public function testCreatePageShouldPersistGivenTransfer()
    {
        $cmsPageSaverMock = $this->createCmsPageSaverMock();

        $cmsPageEntityMock = $this->createCmsPageEntityMock();
        $cmsPageEntityMock->setIdCmsPage(1);
        $cmsPageEntityMock->expects($this->once())
            ->method('save');
        $cmsPageSaverMock->expects($this->once())
            ->method('createCmsPageEntity')
            ->willReturn($cmsPageEntityMock);

        $cmsPageLocalizedAttributesEntityMock = $this->createCmsPageLocalizedAttributesEntityMock();
        $cmsPageLocalizedAttributesEntityMock->expects($this->exactly(2))
            ->method('save');
        $cmsPageSaverMock->expects($this->once())
            ->method('createCmsPageLocalizedAttributesEntity')
            ->willReturn($cmsPageLocalizedAttributesEntityMock);

        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->addPageAttribute(new CmsPageAttributesTransfer());
        $cmsPageTransfer->addMetaAttribute(new CmsPageMetaAttributesTransfer());
        $cmsPageTransfer->setStoreRelation(new StoreRelationTransfer());

        $idCmsPage = $cmsPageSaverMock->createPage($cmsPageTransfer);

        $this->assertEquals($cmsPageEntityMock->getIdCmsPage(), $idCmsPage);
    }

    /**
     * @return void
     */
    public function testUpdatePageShouldUpdateExistingEntityWithNewData()
    {
        $touchFacadeMock = $this->createTouchFacadeMock();
        $touchFacadeMock->expects($this->once())
            ->method('touchActive');

        $cmsPageSaverMock = $this->createCmsPageSaverMock(null, $touchFacadeMock);

        $cmsPageEntityMock = $this->createCmsPageEntityMock();
        $cmsPageEntityMock->setIsActive(true);

        $localizedAttributesEntityMock = $this->createCmsPageLocalizedAttributesEntityMock();
        $localizedAttributesEntityMock->setIdCmsPageLocalizedAttributes(1);
        $cmsPageEntityMock->addSpyCmsPageLocalizedAttributes($localizedAttributesEntityMock);

        $urlEntity = new SpyUrl();
        $urlEntity->setFkLocale(1);
        $cmsPageEntityMock->addSpyUrl($urlEntity);

        $cmsPageSaverMock->expects($this->once())
            ->method('getCmsPageEntity')
            ->willReturn($cmsPageEntityMock);

        $cmsPageEntityMock->setIdCmsPage(1);

        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->setIsSearchable(false);

        $idStores = [1, 3];

        $storeRelationTransfer = new StoreRelationTransfer();
        $storeRelationTransfer->setIdEntity(1);
        $storeRelationTransfer->setIdStores($idStores);

        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->setIdCmsPageLocalizedAttributes(1);
        $cmsPageAttributesTransfer->setUrl('/en/english');
        $cmsPageAttributesTransfer->setName('english name');
        $cmsPageAttributesTransfer->setFkLocale(1);
        $cmsPageAttributesTransfer->setIdCmsPage(1);
        $cmsPageTransfer->setStoreRelation($storeRelationTransfer);

        $cmsPageTransfer->addPageAttribute($cmsPageAttributesTransfer);

        $cmsPageTransfer = $cmsPageSaverMock->updatePage($cmsPageTransfer);

        $cmsPageAttributesEntity = $cmsPageEntityMock->getSpyCmsPageLocalizedAttributess()[0];
        $this->assertEquals($cmsPageAttributesEntity->getName(), $cmsPageAttributesTransfer->getName());
        $this->assertEquals($urlEntity->getUrl(), $cmsPageAttributesTransfer->getUrlPrefix());
        $this->assertEquals($cmsPageTransfer->getStoreRelation()->getIdStores(), $idStores);
    }

    /**
     * @param \Spryker\Zed\Url\Business\UrlFacadeInterface|null $urlFacadeMock
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface|null $touchFacadeMock
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface|null $cmsQueryContainerMock
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface|null $cmsPageUrlBuilderMock
     * @param \Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface|null $cmsGlossarySaverMock
     * @param \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface|null $templateManagerMock
     * @param \Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationWriterInterface|null $cmsPageStoreRelationWriterMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Cms\Business\Page\CmsPageSaver
     */
    protected function createCmsPageSaverMock(
        ?UrlFacadeInterface $urlFacadeMock = null,
        ?CmsToTouchFacadeInterface $touchFacadeMock = null,
        ?CmsQueryContainerInterface $cmsQueryContainerMock = null,
        ?CmsPageUrlBuilderInterface $cmsPageUrlBuilderMock = null,
        ?CmsGlossarySaverInterface $cmsGlossarySaverMock = null,
        ?TemplateManagerInterface $templateManagerMock = null,
        ?CmsPageStoreRelationWriterInterface $cmsPageStoreRelationWriterMock = null
    ) {
        if ($urlFacadeMock === null) {
            $urlFacadeMock = $this->createUrlFacadeMock();
        }

        if ($touchFacadeMock === null) {
            $touchFacadeMock = $this->createTouchFacadeMock();
        }

        if ($cmsQueryContainerMock === null) {
            $cmsQueryContainerMock = $this->createCmsQueryContainerMock();
        }

        if ($cmsPageUrlBuilderMock === null) {
            $cmsPageUrlBuilderMock = $this->createCmsPageUrlBuilderMock();
        }

        if ($cmsGlossarySaverMock === null) {
            $cmsGlossarySaverMock = $this->createCmsGlossarySaverMock();
        }

        if ($templateManagerMock === null) {
            $templateManagerMock = $this->createTemplateManagerMock();
        }

        if ($cmsPageStoreRelationWriterMock === null) {
            $cmsPageStoreRelationWriterMock = $this->createCmsPageStoreRelationWriterMock();
        }

        $templateManagerMock->expects($this->any())
            ->method('getTemplateById')
            ->willReturn((new CmsTemplateTransfer())->setTemplatePath('template_path'));

        return $this->getMockBuilder(CmsPageSaver::class)
            ->setConstructorArgs([
                $urlFacadeMock,
                $touchFacadeMock,
                $cmsQueryContainerMock,
                $cmsPageUrlBuilderMock,
                $cmsGlossarySaverMock,
                $templateManagerMock,
                $cmsPageStoreRelationWriterMock,
            ])
            ->setMethods([
                'getCmsPageEntity',
                'createCmsPageEntity',
                'createCmsPageLocalizedAttributesEntity',
                'checkTemplateFileExists',
            ])
            ->getMock();
    }
}

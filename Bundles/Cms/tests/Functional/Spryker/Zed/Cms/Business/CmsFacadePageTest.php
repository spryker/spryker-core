<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Cms\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\Cms\Business\CmsFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Cms
 * @group Business
 * @group CmsFacadePageTest
 */
class CmsFacadePageTest extends Test
{

    /**
     * @var CmsFacade
     */
    protected $cmsFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cmsFacade = new CmsFacade();
    }


    /**
     * @return void
     */
    public function testSaveCmsGlossaryShouldPersistGivenGlossary()
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);

        $cmsGlossaryTransfer = $this->cmsFacade->getPageGlossaryAttributes($idCmsPage);

        $updatedCmsGlossaryTransfer = $this->cmsFacade->saveCmsGlossary($cmsGlossaryTransfer);

        $this->cmsFacade->saveCmsGlossary($updatedCmsGlossaryTransfer);
    }

    /**
     * @return void
     */
    public function testCreatePageShouldPersistGivenCmsPage()
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);
        $persistedCmsPageTransfer = $this->cmsFacade->getCmsPageById($idCmsPage);

        $this->assertEquals($persistedCmsPageTransfer->getFkTemplate(), $cmsPageTransfer->getFkTemplate());
        $this->assertEquals($persistedCmsPageTransfer->getIsActive(), $cmsPageTransfer->getIsActive());
        $this->assertEquals($persistedCmsPageTransfer->getIsSearchable(), $cmsPageTransfer->getIsSearchable());
        $this->assertNotEmpty($persistedCmsPageTransfer->getFkPage());

        foreach ($cmsPageTransfer->getPageAttributes() as $cmsPageAttributesTransfer) {
            foreach ($persistedCmsPageTransfer->getPageAttributes() as $persisteCmsPageAttributesTransfer) {
                if ($cmsPageAttributesTransfer->getLocaleName() !== $persisteCmsPageAttributesTransfer->getLocaleName()) {
                    continue;
                }
                $this->assertEquals($cmsPageAttributesTransfer->getName(), $persisteCmsPageAttributesTransfer->getName());
                $this->assertEquals($cmsPageAttributesTransfer->getUrlPrefix(), $persisteCmsPageAttributesTransfer->getUrlPrefix());
                $this->assertEquals($cmsPageAttributesTransfer->getUrl(), $persisteCmsPageAttributesTransfer->getUrl());
                $this->assertEquals($persistedCmsPageTransfer->getFkPage(), $persisteCmsPageAttributesTransfer->getIdCmsPage());
            }
        }

        foreach ($cmsPageTransfer->getMetaAttributes() as $cmsPageMetaAttributesTransfer) {
            foreach ($persistedCmsPageTransfer->getMetaAttributes() as $persistedCmsPageMetaAttributesTransfer) {
                if ($persistedCmsPageMetaAttributesTransfer->getLocaleName() !== $cmsPageMetaAttributesTransfer->getLocaleName()) {
                    continue;
                }
                $this->assertEquals($cmsPageMetaAttributesTransfer->getMetaDescription(), $persistedCmsPageMetaAttributesTransfer->getMetaDescription());
                $this->assertEquals($cmsPageMetaAttributesTransfer->getMetaTitle(), $persistedCmsPageMetaAttributesTransfer->getMetaTitle());
                $this->assertEquals($cmsPageMetaAttributesTransfer->getMetaKeywords(), $persistedCmsPageMetaAttributesTransfer->getMetaKeywords());
            }
        }
    }


    /**
     * @param array $fixtures
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function createCmsPageTransfer(array $fixtures)
    {
        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->fromArray($fixtures, true);

        return $cmsPageTransfer;

    }

    /**
     * @return array
     */
    protected function createCmsPageTransferFixtures()
    {
        $fixtures = [
            CmsPageTransfer::IS_ACTIVE => 1,
            CmsPageTransfer::FK_TEMPLATE => 1,
            CmsPageTransfer::IS_SEARCHABLE => 1,
            CmsPageTransfer::PAGE_ATTRIBUTES => [
                [
                    CmsPageAttributesTransfer::URL => '/en/function-test',
                    CmsPageAttributesTransfer::NAME => 'functional test',
                    CmsPageAttributesTransfer::LOCALE_NAME => 'en_US',
                    CmsPageAttributesTransfer::URL_PREFIX => '/en/',
                    CmsPageAttributesTransfer::FK_LOCALE => 66,
                ],
                [
                    CmsPageAttributesTransfer::URL => '/de/function-test',
                    CmsPageAttributesTransfer::NAME => 'functional test',
                    CmsPageAttributesTransfer::LOCALE_NAME => 'de_DE',
                    CmsPageAttributesTransfer::URL_PREFIX => '/de/',
                    CmsPageAttributesTransfer::FK_LOCALE => 46,
                ],
            ],
            CmsPageTransfer::META_ATTRIBUTES => [
                [
                    CmsPageMetaAttributesTransfer::META_TITLE => 'title english',
                    CmsPageMetaAttributesTransfer::META_KEYWORDS => 'key, word',
                    CmsPageMetaAttributesTransfer::META_DESCRIPTION => 'english description',
                    CmsPageMetaAttributesTransfer::LOCALE_NAME => 'en_US',
                    CmsPageAttributesTransfer::FK_LOCALE => 66,
                ],
                [
                    CmsPageMetaAttributesTransfer::META_TITLE => 'title german',
                    CmsPageMetaAttributesTransfer::META_KEYWORDS => 'key, word',
                    CmsPageMetaAttributesTransfer::META_DESCRIPTION => 'german description',
                    CmsPageMetaAttributesTransfer::LOCALE_NAME => 'de_DE',
                    CmsPageAttributesTransfer::FK_LOCALE => 46,
                ]
            ]
        ];
        return $fixtures;
    }


}


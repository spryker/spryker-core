<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Cms\Business;

use Codeception\TestCase\Test;
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

    const CMS_PAGE_NEW_TITLE = 'new title';
    const CMS_PAGE_NEW_KEY_WORDS = 'new key words';
    const CMS_PAGE_NEW_DESCRIPTION = 'new description';

    /**
     * @var \Spryker\Zed\Cms\Business\CmsFacade
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
    public function testSaveCmsGlossaryShouldPersistUpdatedTranslations()
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);

        $cmsGlossaryTransfer = $this->cmsFacade->findPageGlossaryAttributes($idCmsPage);

        $cmsGlossaryAttributesTransfer = $cmsGlossaryTransfer->getGlossaryAttributes()[0];

        $translationFixtures = $this->getTranslationFixtures();

        $translations = $cmsGlossaryAttributesTransfer->getTranslations();
        foreach ($translations as $cmsPlaceholderTranslationTransfer) {
            $cmsPlaceholderTranslationTransfer->setTranslation(
                $translationFixtures[$cmsPlaceholderTranslationTransfer->getLocaleName()]
            );
        }

        $updatedCmsGlossaryTransfer = $this->cmsFacade->saveCmsGlossary($cmsGlossaryTransfer);

        $cmsGlossaryAttributesTransfer = $updatedCmsGlossaryTransfer->getGlossaryAttributes()[0];

        $translations = $cmsGlossaryAttributesTransfer->getTranslations();
        foreach ($translations as $cmsPlaceholderTranslationTransfer) {
            $this->assertEquals(
                $translationFixtures[$cmsPlaceholderTranslationTransfer->getLocaleName()],
                $cmsPlaceholderTranslationTransfer->getTranslation()
            );
        }
    }

    /**
     * @return void
     */
    public function testCreatePageShouldPersistGivenCmsPage()
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);
        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        $this->assertEquals($persistedCmsPageTransfer->getFkTemplate(), $cmsPageTransfer->getFkTemplate());
        $this->assertEquals($persistedCmsPageTransfer->getIsActive(), $cmsPageTransfer->getIsActive());
        $this->assertEquals($persistedCmsPageTransfer->getIsSearchable(), $cmsPageTransfer->getIsSearchable());
        $this->assertNotEmpty($persistedCmsPageTransfer->getFkPage());

        $this->assertPageAttributes($cmsPageTransfer, $persistedCmsPageTransfer);
        $this->assertPageMetaAttributes($cmsPageTransfer, $persistedCmsPageTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePageShouldUpdatePageWithNewData()
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);
        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        $persistedCmsPageMetaAttributes = $persistedCmsPageTransfer->getMetaAttributes()[0];
        $persistedCmsPageMetaAttributes->setMetaTitle(self::CMS_PAGE_NEW_TITLE);
        $persistedCmsPageMetaAttributes->setMetaKeywords(self::CMS_PAGE_NEW_KEY_WORDS);
        $persistedCmsPageMetaAttributes->setMetaDescription(self::CMS_PAGE_NEW_DESCRIPTION);

        $persistedCmsPageAttributes = $persistedCmsPageTransfer->getPageAttributes()[0];
        $persistedCmsPageAttributes->setName('new page name');
        $persistedCmsPageAttributes->setUrl('updated-url');

        $updatedCmsPageTransfer = $this->cmsFacade->updatePage($persistedCmsPageTransfer);

        $updatedCmsPageMetaAttributes = $updatedCmsPageTransfer->getMetaAttributes()[0];
        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaDescription(), $persistedCmsPageMetaAttributes->getMetaDescription());
        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaKeywords(), $persistedCmsPageMetaAttributes->getMetaKeywords());
        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaTitle(), $persistedCmsPageMetaAttributes->getMetaTitle());

        $updatedCmsPageAttributes = $persistedCmsPageTransfer->getPageAttributes()[0];
        $this->assertEquals($updatedCmsPageAttributes->getName(), $persistedCmsPageAttributes->getName());
        $this->assertEquals($updatedCmsPageAttributes->getUrl(), $persistedCmsPageAttributes->getUrl());
    }

    /**
     * @return void
     */
    public function testActivatePageShouldActivateInactivePage()
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $fixtures[CmsPageTransfer::IS_ACTIVE] = false;
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);

        $cmsGlossaryTransfer = $this->cmsFacade->findPageGlossaryAttributes($idCmsPage);

        $cmsGlossaryAttributesTransfer = $cmsGlossaryTransfer->getGlossaryAttributes()[0];

        $translationFixtures = $this->getTranslationFixtures();

        $translations = $cmsGlossaryAttributesTransfer->getTranslations();
        foreach ($translations as $cmsPlaceholderTranslationTransfer) {
            $cmsPlaceholderTranslationTransfer->setTranslation(
                $translationFixtures[$cmsPlaceholderTranslationTransfer->getLocaleName()]
            );
        }
        $this->cmsFacade->saveCmsGlossary($cmsGlossaryTransfer);

        $this->cmsFacade->activatePage($idCmsPage);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        $this->assertTrue($persistedCmsPageTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeActivatePageShouldActivateInactivePage()
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);

        $this->cmsFacade->deactivatePage($idCmsPage);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        $this->assertFalse($persistedCmsPageTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testGetPageUrlPrefixShouldBuildUrlPrefixFromGivenLocalName()
    {
         $cmsPageAttributeTransfer = new CmsPageAttributesTransfer();
         $cmsPageAttributeTransfer->setLocaleName('en_US');

         $urlPrefix = $this->cmsFacade->getPageUrlPrefix($cmsPageAttributeTransfer);

         $this->assertEquals('/en/', $urlPrefix);
    }

    /**
     * @return void
     */
    public function testBuildPageUrlWhenUrlWithouPrefixGivenShouldBuildValidUrl()
    {
        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->setLocaleName('en_US');
        $cmsPageAttributesTransfer->setUrl('test-url-functionl');

        $url = $this->cmsFacade->buildPageUrl($cmsPageAttributesTransfer);

        $this->assertEquals('/en/' . $cmsPageAttributesTransfer->getUrl(), $url);
    }

    /**
     * @return void
     */
    public function testBuildPageUrlWhenUrlWithPrefixGivenShouldBuildValidUrl()
    {
        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->setLocaleName('en_US');
        $cmsPageAttributesTransfer->setUrl('/en/test-url-functionl');

        $url = $this->cmsFacade->buildPageUrl($cmsPageAttributesTransfer);

        $this->assertEquals($cmsPageAttributesTransfer->getUrl(), $url);
    }

    /**
     * @return void
     */
    public function testPublishPageShouldPersistCmsVersion()
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $cmsVersionTransfer = $this->cmsFacade->publishAndVersion($idCmsPage);

        $this->assertNotNull($cmsVersionTransfer);
        $this->assertEquals($cmsVersionTransfer->getFkCmsPage(), $idCmsPage);
        $this->assertEquals($cmsVersionTransfer->getVersion(), 1);
        $this->assertNotEmpty($cmsVersionTransfer->getData());
    }

    /**
     * @return void
     */
    public function testPublishPageShouldGetNewVersion()
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $cmsVersionTransferOne = $this->cmsFacade->publishAndVersion($idCmsPage);
        $cmsVersionTransferTwo = $this->cmsFacade->publishAndVersion($idCmsPage);

        $this->assertGreaterThan($cmsVersionTransferOne->getVersion(), $cmsVersionTransferTwo->getVersion());
    }

    /**
     * @return void
     */
    public function testRollbackPageShouldGetOldData()
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $cmsVersionTransferOne = $this->cmsFacade->publishAndVersion($idCmsPage);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        foreach ($persistedCmsPageTransfer->getMetaAttributes() as $metaAttribute) {
            $metaAttribute->setMetaTitle(static::CMS_PAGE_NEW_TITLE);
            $metaAttribute->setMetaKeywords(static::CMS_PAGE_NEW_KEY_WORDS);
            $metaAttribute->setMetaDescription(static::CMS_PAGE_NEW_DESCRIPTION);
        }

        $updatedPageTransfer = $this->cmsFacade->updatePage($persistedCmsPageTransfer);
        $updatedCmsPageMetaAttributes = $updatedPageTransfer->getMetaAttributes()[0];

        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaDescription(), static::CMS_PAGE_NEW_DESCRIPTION);
        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaKeywords(), static::CMS_PAGE_NEW_KEY_WORDS);
        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaTitle(), static::CMS_PAGE_NEW_TITLE);

        $this->cmsFacade->publishAndVersion($idCmsPage);
        $this->cmsFacade->rollback($idCmsPage, $cmsVersionTransferOne->getVersion());

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);
        $persistedCmsPageMetaAttributes = $persistedCmsPageTransfer->getMetaAttributes()[0];

        $this->assertNotEquals($persistedCmsPageMetaAttributes->getMetaDescription(), static::CMS_PAGE_NEW_DESCRIPTION);
        $this->assertNotEquals($persistedCmsPageMetaAttributes->getMetaKeywords(), static::CMS_PAGE_NEW_KEY_WORDS);
        $this->assertNotEquals($persistedCmsPageMetaAttributes->getMetaTitle(), static::CMS_PAGE_NEW_TITLE);
    }

    /**
     * @return void
     */
    public function testRevertPageShouldGetOldData()
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $this->cmsFacade->publishAndVersion($idCmsPage);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        foreach ($persistedCmsPageTransfer->getMetaAttributes() as $metaAttribute) {
            $metaAttribute->setMetaTitle(static::CMS_PAGE_NEW_TITLE);
            $metaAttribute->setMetaKeywords(static::CMS_PAGE_NEW_KEY_WORDS);
            $metaAttribute->setMetaDescription(static::CMS_PAGE_NEW_DESCRIPTION);
        }

        $updatedPageTransfer = $this->cmsFacade->updatePage($persistedCmsPageTransfer);
        $updatedCmsPageMetaAttributes = $updatedPageTransfer->getMetaAttributes()[0];

        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaDescription(), static::CMS_PAGE_NEW_DESCRIPTION);
        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaKeywords(), static::CMS_PAGE_NEW_KEY_WORDS);
        $this->assertEquals($updatedCmsPageMetaAttributes->getMetaTitle(), static::CMS_PAGE_NEW_TITLE);

        $this->cmsFacade->revert($idCmsPage);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);
        $persistedCmsPageMetaAttributes = $persistedCmsPageTransfer->getMetaAttributes()[0];

        $this->assertNotEquals($persistedCmsPageMetaAttributes->getMetaDescription(), static::CMS_PAGE_NEW_DESCRIPTION);
        $this->assertNotEquals($persistedCmsPageMetaAttributes->getMetaKeywords(), static::CMS_PAGE_NEW_KEY_WORDS);
        $this->assertNotEquals($persistedCmsPageMetaAttributes->getMetaTitle(), static::CMS_PAGE_NEW_TITLE);
    }

    /**
     * @return void
     */
    public function testFindLatestCmsVersionReturnsLatestVersion()
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $cmsVersionTransferOne = $this->cmsFacade->publishAndVersion($idCmsPage);
        $this->cmsFacade->publishAndVersion($idCmsPage);

        $cmsVersionTransferTwo = $this->cmsFacade->findLatestCmsVersionByIdCmsPage($idCmsPage);

        $this->assertGreaterThan($cmsVersionTransferOne->getVersion(), $cmsVersionTransferTwo->getVersion());
    }

    /**
     * @return void
     */
    public function testFindAllCmsVersionByReturnsAllVersions()
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $this->cmsFacade->publishAndVersion($idCmsPage);
        $this->cmsFacade->publishAndVersion($idCmsPage);

        $versions = $this->cmsFacade->findAllCmsVersionByIdCmsPage($idCmsPage);

        $this->assertEquals(count($versions), 2);
    }

    /**
     * @return void
     */
    public function testFindCmsVersionByVersionNumberReturnsSameVersion()
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $this->cmsFacade->publishAndVersion($idCmsPage);
        $this->cmsFacade->publishAndVersion($idCmsPage);

        $cmsVersion = $this->cmsFacade->findCmsVersionByIdCmsPageAndVersion($idCmsPage,1);

        $this->assertEquals($cmsVersion->getVersion(), 1);
    }

    /**
     * @return int
     */
    protected function createCmsPageWithGlossaryAttributes()
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);
        $cmsGlossaryTransfer = $this->cmsFacade->findPageGlossaryAttributes($idCmsPage);

        $cmsGlossaryAttributesTransfer = $cmsGlossaryTransfer->getGlossaryAttributes()[0];

        $translationFixtures = $this->getTranslationFixtures();

        $translations = $cmsGlossaryAttributesTransfer->getTranslations();
        foreach ($translations as $cmsPlaceholderTranslationTransfer) {
            $cmsPlaceholderTranslationTransfer->setTranslation($translationFixtures[$cmsPlaceholderTranslationTransfer->getLocaleName()]);
        }
        $this->cmsFacade->saveCmsGlossary($cmsGlossaryTransfer);

        return $idCmsPage;
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
                ],
            ],
        ];
        return $fixtures;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Generated\Shared\Transfer\CmsPageTransfer $persistedCmsPageTransfer
     *
     * @return void
     */
    protected function assertPageAttributes(CmsPageTransfer $cmsPageTransfer, CmsPageTransfer $persistedCmsPageTransfer)
    {
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
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Generated\Shared\Transfer\CmsPageTransfer $persistedCmsPageTransfer
     *
     * @return void
     */
    protected function assertPageMetaAttributes(CmsPageTransfer $cmsPageTransfer, CmsPageTransfer $persistedCmsPageTransfer)
    {
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
     * @return array
     */
    protected function getTranslationFixtures()
    {
        $translationFixtures = [
            'en_US' => 'english translation',
            'de_DE' => 'german translation',
        ];

        return $translationFixtures;
    }

}

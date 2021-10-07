<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Cms\Business\CmsFacade;
use Spryker\Zed\Store\Business\StoreFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Facade
 * @group CmsFacadePageTest
 * Add your own group annotations below this line
 */
class CmsFacadePageTest extends Unit
{
    /**
     * @var string
     */
    public const CMS_PAGE_NEW_TITLE = 'new title';
    /**
     * @var string
     */
    public const CMS_PAGE_NEW_KEY_WORDS = 'new key words';
    /**
     * @var string
     */
    public const CMS_PAGE_NEW_DESCRIPTION = 'new description';

    /**
     * @var \Spryker\Zed\Cms\Business\CmsFacade
     */
    protected $cmsFacade;

    /**
     * @var \SprykerTest\Zed\Cms\CmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cmsFacade = new CmsFacade();
    }

    /**
     * @return void
     */
    public function testSaveCmsGlossaryShouldPersistUpdatedTranslations(): void
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
            $this->assertSame(
                $translationFixtures[$cmsPlaceholderTranslationTransfer->getLocaleName()],
                $cmsPlaceholderTranslationTransfer->getTranslation()
            );
        }
    }

    /**
     * @return void
     */
    public function testCreatePageShouldPersistGivenCmsPage(): void
    {
        $fixtures = $this->createCmsPageTransferFixtures();
        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);

        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);
        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        $this->assertSame($persistedCmsPageTransfer->getFkTemplate(), $cmsPageTransfer->getFkTemplate());
        $this->assertSame($persistedCmsPageTransfer->getIsActive(), $cmsPageTransfer->getIsActive());
        $this->assertSame($persistedCmsPageTransfer->getIsSearchable(), $cmsPageTransfer->getIsSearchable());
        $this->assertNotEmpty($persistedCmsPageTransfer->getFkPage());

        $this->assertPageAttributes($cmsPageTransfer, $persistedCmsPageTransfer);
        $this->assertPageMetaAttributes($cmsPageTransfer, $persistedCmsPageTransfer);
    }

    /**
     * @return void
     */
    public function testUpdatePageShouldUpdatePageWithNewData(): void
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
        $this->assertSame($updatedCmsPageMetaAttributes->getMetaDescription(), $persistedCmsPageMetaAttributes->getMetaDescription());
        $this->assertSame($updatedCmsPageMetaAttributes->getMetaKeywords(), $persistedCmsPageMetaAttributes->getMetaKeywords());
        $this->assertSame($updatedCmsPageMetaAttributes->getMetaTitle(), $persistedCmsPageMetaAttributes->getMetaTitle());

        $updatedCmsPageAttributes = $persistedCmsPageTransfer->getPageAttributes()[0];
        $this->assertSame($updatedCmsPageAttributes->getName(), $persistedCmsPageAttributes->getName());
        $this->assertSame($updatedCmsPageAttributes->getUrl(), $persistedCmsPageAttributes->getUrl());
    }

    /**
     * @return void
     */
    public function testActivatePageShouldActivateInactivePage(): void
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
    public function testDeActivatePageShouldActivateInactivePage(): void
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
    public function testGetPageUrlPrefixShouldBuildUrlPrefixFromGivenLocalName(): void
    {
        $cmsPageAttributeTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributeTransfer->setLocaleName('en_US');

        $urlPrefix = $this->cmsFacade->getPageUrlPrefix($cmsPageAttributeTransfer);

        $this->assertSame('', $urlPrefix);
    }

    /**
     * @return void
     */
    public function testBuildPageUrlWhenUrlWithoutPrefixGivenShouldBuildValidUrl(): void
    {
        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->setLocaleName('en_US');
        $cmsPageAttributesTransfer->setUrl('test-url-functionl');

        $url = $this->cmsFacade->buildPageUrl($cmsPageAttributesTransfer);

        $this->assertSame($cmsPageAttributesTransfer->getUrl(), $url);
    }

    /**
     * @return void
     */
    public function testBuildPageUrlWhenUrlWithPrefixGivenShouldBuildValidUrl(): void
    {
        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->setLocaleName('en_US');
        $cmsPageAttributesTransfer->setUrl('/en/test-url-functionl');

        $url = $this->cmsFacade->buildPageUrl($cmsPageAttributesTransfer);

        $this->assertSame($cmsPageAttributesTransfer->getUrl(), $url);
    }

    /**
     * @return void
     */
    public function testPublishPageShouldPersistCmsVersion(): void
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $cmsVersionTransfer = $this->cmsFacade->publishWithVersion($idCmsPage);

        $this->assertNotNull($cmsVersionTransfer);
        $this->assertSame($cmsVersionTransfer->getFkCmsPage(), $idCmsPage);
        $this->assertSame($cmsVersionTransfer->getVersion(), 1);
        $this->assertNotEmpty($cmsVersionTransfer->getData());
    }

    /**
     * @return void
     */
    public function testPublishPageShouldGetNewVersion(): void
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $cmsVersionTransferOne = $this->cmsFacade->publishWithVersion($idCmsPage);
        $cmsVersionTransferTwo = $this->cmsFacade->publishWithVersion($idCmsPage);

        $this->assertGreaterThan($cmsVersionTransferOne->getVersion(), $cmsVersionTransferTwo->getVersion());
    }

    /**
     * @return void
     */
    public function testRollbackPageShouldGetOldData(): void
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $cmsVersionTransferOne = $this->cmsFacade->publishWithVersion($idCmsPage);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        foreach ($persistedCmsPageTransfer->getMetaAttributes() as $metaAttribute) {
            $metaAttribute->setMetaTitle(static::CMS_PAGE_NEW_TITLE);
            $metaAttribute->setMetaKeywords(static::CMS_PAGE_NEW_KEY_WORDS);
            $metaAttribute->setMetaDescription(static::CMS_PAGE_NEW_DESCRIPTION);
        }

        $updatedPageTransfer = $this->cmsFacade->updatePage($persistedCmsPageTransfer);
        $updatedCmsPageMetaAttributes = $updatedPageTransfer->getMetaAttributes()[0];

        $this->assertSame($updatedCmsPageMetaAttributes->getMetaDescription(), static::CMS_PAGE_NEW_DESCRIPTION);
        $this->assertSame($updatedCmsPageMetaAttributes->getMetaKeywords(), static::CMS_PAGE_NEW_KEY_WORDS);
        $this->assertSame($updatedCmsPageMetaAttributes->getMetaTitle(), static::CMS_PAGE_NEW_TITLE);

        $this->cmsFacade->publishWithVersion($idCmsPage);
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
    public function testRevertPageShouldGetOldData(): void
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $this->cmsFacade->publishWithVersion($idCmsPage);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        foreach ($persistedCmsPageTransfer->getMetaAttributes() as $metaAttribute) {
            $metaAttribute->setMetaTitle(static::CMS_PAGE_NEW_TITLE);
            $metaAttribute->setMetaKeywords(static::CMS_PAGE_NEW_KEY_WORDS);
            $metaAttribute->setMetaDescription(static::CMS_PAGE_NEW_DESCRIPTION);
        }

        $updatedPageTransfer = $this->cmsFacade->updatePage($persistedCmsPageTransfer);
        $updatedCmsPageMetaAttributes = $updatedPageTransfer->getMetaAttributes()[0];

        $this->assertSame($updatedCmsPageMetaAttributes->getMetaDescription(), static::CMS_PAGE_NEW_DESCRIPTION);
        $this->assertSame($updatedCmsPageMetaAttributes->getMetaKeywords(), static::CMS_PAGE_NEW_KEY_WORDS);
        $this->assertSame($updatedCmsPageMetaAttributes->getMetaTitle(), static::CMS_PAGE_NEW_TITLE);

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
    public function testFindLatestCmsVersionReturnsLatestVersion(): void
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $cmsVersionTransferOne = $this->cmsFacade->publishWithVersion($idCmsPage);
        $this->cmsFacade->publishWithVersion($idCmsPage);

        $cmsVersionTransferTwo = $this->cmsFacade->findLatestCmsVersionByIdCmsPage($idCmsPage);

        $this->assertGreaterThan($cmsVersionTransferOne->getVersion(), $cmsVersionTransferTwo->getVersion());
    }

    /**
     * @return void
     */
    public function testFindAllCmsVersionByReturnsAllVersions(): void
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $this->cmsFacade->publishWithVersion($idCmsPage);
        $this->cmsFacade->publishWithVersion($idCmsPage);

        $versions = $this->cmsFacade->findAllCmsVersionByIdCmsPage($idCmsPage);

        $this->assertSame(count($versions), 2);
    }

    /**
     * @return void
     */
    public function testFindCmsVersionByVersionNumberReturnsSameVersion(): void
    {
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $this->cmsFacade->publishWithVersion($idCmsPage);
        $this->cmsFacade->publishWithVersion($idCmsPage);

        $cmsVersion = $this->cmsFacade->findCmsVersionByIdCmsPageAndVersion($idCmsPage, 1);

        $this->assertSame($cmsVersion->getVersion(), 1);
    }

    /**
     * @return void
     */
    public function testGetCmsVersionDataRetrievesDraftDataFromDatabase(): void
    {
        // Arrange
        $idCmsPage = $this->createCmsPageWithGlossaryAttributes();
        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);

        foreach ($persistedCmsPageTransfer->getMetaAttributes() as $metaAttribute) {
            $metaAttribute->setMetaTitle(static::CMS_PAGE_NEW_TITLE);
            $metaAttribute->setMetaKeywords(static::CMS_PAGE_NEW_KEY_WORDS);
            $metaAttribute->setMetaDescription(static::CMS_PAGE_NEW_DESCRIPTION);
        }

        $expectedCmsVersionData = $this->cmsFacade->updatePage($persistedCmsPageTransfer);

        // Act
        $actualCmsVersionData = $this->cmsFacade->getCmsVersionData($idCmsPage);

        // Assert
        $expectedCmsPageVersionMetaAttributes = $expectedCmsVersionData->getMetaAttributes()[0];
        $actualCmsPageVersionMetaAttributes = $actualCmsVersionData->getCmsPage()->getMetaAttributes()[0];
        $this->assertEquals($expectedCmsPageVersionMetaAttributes->getMetaDescription(), $actualCmsPageVersionMetaAttributes->getMetaDescription());
        $this->assertEquals($expectedCmsPageVersionMetaAttributes->getMetaKeywords(), $actualCmsPageVersionMetaAttributes->getMetaKeywords());
        $this->assertEquals($expectedCmsPageVersionMetaAttributes->getMetaTitle(), $actualCmsPageVersionMetaAttributes->getMetaTitle());
    }

    /**
     * @return int
     */
    protected function createCmsPageWithGlossaryAttributes(): int
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
     * @return void
     */
    public function testCreateCmsPageSavesStoreRelation(): void
    {
        $storeFacade = $this->createStoreFacade();

        $stores = $storeFacade->getAllStores();

        $expectedIdStores = [];

        foreach ($stores as $storeTransfer) {
            $expectedIdStores[] = $storeTransfer->getIdStore();
        }

        $storeRelationSeed = [
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_STORES => $expectedIdStores,
            ],
        ];

        $fixtures = $this->createCmsPageTransferFixtures();
        $fixtures += $storeRelationSeed;

        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);
        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);
        $resultIdStores = $persistedCmsPageTransfer->getStoreRelation()->getIdStores();

        sort($resultIdStores);
        $this->assertEquals($expectedIdStores, $resultIdStores);
    }

    /**
     * @dataProvider relationUpdateFixtures
     *
     * @param array<int> $originalRelation
     * @param array<int> $modifiedRelation
     *
     * @return void
     */
    public function testUpdateCmsPageUpdatesStoreRelation(array $originalRelation, array $modifiedRelation): void
    {
        $storeRelationSeed = [
            CmsPageTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_STORES => $originalRelation,
            ],
        ];

        $fixtures = $this->createCmsPageTransferFixtures();
        $fixtures += $storeRelationSeed;

        $cmsPageTransfer = $this->createCmsPageTransfer($fixtures);
        $idCmsPage = $this->cmsFacade->createPage($cmsPageTransfer);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($idCmsPage);
        $persistedCmsPageTransfer->getStoreRelation()->setIdStores($modifiedRelation);

        $this->cmsFacade->updatePage($persistedCmsPageTransfer);

        $persistedCmsPageTransfer = $this->cmsFacade->findCmsPageById($persistedCmsPageTransfer->getFkPage());
        $resultIdStores = $persistedCmsPageTransfer->getStoreRelation()->getIdStores();

        // Assert
        sort($resultIdStores);
        $this->assertEquals($modifiedRelation, $resultIdStores);
    }

    /**
     * @param array $fixtures
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function createCmsPageTransfer(array $fixtures): CmsPageTransfer
    {
        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->fromArray($fixtures, true);

        return $cmsPageTransfer;
    }

    /**
     * @return array
     */
    protected function createCmsPageTransferFixtures(): array
    {
        $fixtures = [
            CmsPageTransfer::IS_ACTIVE => true,
            CmsPageTransfer::FK_TEMPLATE => 1,
            CmsPageTransfer::IS_SEARCHABLE => true,
            CmsPageTransfer::PAGE_ATTRIBUTES => [
                [
                    CmsPageAttributesTransfer::URL => '/en/function-test',
                    CmsPageAttributesTransfer::NAME => 'functional test',
                    CmsPageAttributesTransfer::LOCALE_NAME => 'en_US',
                    CmsPageAttributesTransfer::URL_PREFIX => '',
                    CmsPageAttributesTransfer::FK_LOCALE => 66,
                ],
                [
                    CmsPageAttributesTransfer::URL => '/de/function-test',
                    CmsPageAttributesTransfer::NAME => 'functional test',
                    CmsPageAttributesTransfer::LOCALE_NAME => 'de_DE',
                    CmsPageAttributesTransfer::URL_PREFIX => '',
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
    protected function assertPageAttributes(CmsPageTransfer $cmsPageTransfer, CmsPageTransfer $persistedCmsPageTransfer): void
    {
        foreach ($cmsPageTransfer->getPageAttributes() as $cmsPageAttributesTransfer) {
            foreach ($persistedCmsPageTransfer->getPageAttributes() as $persistedCmsPageAttributesTransfer) {
                if ($cmsPageAttributesTransfer->getLocaleName() !== $persistedCmsPageAttributesTransfer->getLocaleName()) {
                    continue;
                }
                $this->assertEquals($cmsPageAttributesTransfer->getName(), $persistedCmsPageAttributesTransfer->getName());
                $this->assertEquals($cmsPageAttributesTransfer->getUrlPrefix(), $persistedCmsPageAttributesTransfer->getUrlPrefix());
                $this->assertEquals($cmsPageAttributesTransfer->getUrl(), $persistedCmsPageAttributesTransfer->getUrl());
                $this->assertEquals($persistedCmsPageTransfer->getFkPage(), $persistedCmsPageAttributesTransfer->getIdCmsPage());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Generated\Shared\Transfer\CmsPageTransfer $persistedCmsPageTransfer
     *
     * @return void
     */
    protected function assertPageMetaAttributes(CmsPageTransfer $cmsPageTransfer, CmsPageTransfer $persistedCmsPageTransfer): void
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
    protected function getTranslationFixtures(): array
    {
        $translationFixtures = [
            'en_US' => 'english translation',
            'de_DE' => 'german translation',
        ];

        return $translationFixtures;
    }

    /**
     * @return array
     */
    public function relationUpdateFixtures(): array
    {
        return [
            [
                [1, 2, 3], [2],
            ],
            [
                [1], [1, 2],
            ],
            [
                [2], [1, 3],
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacade
     */
    protected function createStoreFacade(): StoreFacade
    {
        return new StoreFacade();
    }
}

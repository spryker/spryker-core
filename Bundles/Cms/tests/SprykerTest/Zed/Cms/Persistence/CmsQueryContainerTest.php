<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;
use Spryker\Zed\Cms\Persistence\CmsPersistenceFactory;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Persistence
 * @group CmsQueryContainerTest
 * Add your own group annotations below this line
 */
class CmsQueryContainerTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_LOCALE = 'xxx';

    /**
     * @var \SprykerTest\Zed\Cms\CmsPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testQueryAllCmsVersionReturnCorrectQuery(): void
    {
        $cmsQueryContainer = new CmsQueryContainer();
        $cmsQueryContainer->setFactory(new CmsPersistenceFactory());
        $query = $cmsQueryContainer->queryAllCmsVersions();

        $this->assertInstanceOf(SpyCmsVersionQuery::class, $query);
    }

    /**
     * @void
     *
     * @return void
     */
    public function testQueryPagesWithTemplatesForSelectedLocaleReturnsCorrectData(): void
    {
        //Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE]);
        $seedData = [
            CmsPageTransfer::FK_TEMPLATE => 1,
            CmsPageAttributesTransfer::LOCALE_NAME => $localeTransfer->getLocaleName(),
            CmsPageAttributesTransfer::FK_LOCALE => $localeTransfer->getIdLocale(),
        ];
        $this->tester->haveCmsPage($seedData);
        $this->tester->haveCmsPage($seedData);
        $cmsQueryContainer = new CmsQueryContainer();
        $cmsQueryContainer->setFactory(new CmsPersistenceFactory());

        //Act
        $result = $cmsQueryContainer->queryPagesWithTemplatesForSelectedLocale($localeTransfer->getIdLocale())
            ->find()->toArray();

        //Assert
        $this->assertCount(2, $result);
    }

    /**
     * @return void
     */
    public function testQueryLocalizedPagesWithTemplatesReturnsCorrectData(): void
    {
        //Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE]);
        $seedData = [
            CmsPageTransfer::FK_TEMPLATE => 1,
            CmsPageAttributesTransfer::LOCALE_NAME => $localeTransfer->getLocaleName(),
            CmsPageAttributesTransfer::FK_LOCALE => $localeTransfer->getIdLocale(),
        ];
        $this->tester->haveCmsPage($seedData);
        $this->tester->haveCmsPage($seedData);
        $cmsQueryContainer = new CmsQueryContainer();
        $cmsQueryContainer->setFactory(new CmsPersistenceFactory());

        //Act
        $result = $cmsQueryContainer->queryLocalizedPagesWithTemplates()
            ->find()->toArray();

        //Assert
        $this->assertGreaterThanOrEqual(2, count($result));
    }

    /**
     * @return void
     */
    public function testQueryPageWithTemplatesAndUrlsReturnsCorrectData(): void
    {
        //Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE]);
        $seedData = [
            CmsPageTransfer::FK_TEMPLATE => 1,
            CmsPageAttributesTransfer::LOCALE_NAME => $localeTransfer->getLocaleName(),
            CmsPageAttributesTransfer::FK_LOCALE => $localeTransfer->getIdLocale(),
        ];
        $this->tester->haveCmsPage($seedData);
        $this->tester->haveCmsPage($seedData);
        $cmsQueryContainer = new CmsQueryContainer();
        $cmsQueryContainer->setFactory(new CmsPersistenceFactory());

        //Act
        $result = $cmsQueryContainer->queryPageWithTemplatesAndUrls()
            ->find()->toArray();

        //Assert
        $this->assertGreaterThanOrEqual(2, count($result));
    }
}

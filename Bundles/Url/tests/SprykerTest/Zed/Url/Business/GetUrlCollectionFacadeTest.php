<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Url\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\UrlConditionsTransfer;
use Generated\Shared\Transfer\UrlCriteriaTransfer;
use Generated\Shared\Transfer\UrlTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Url
 * @group Business
 * @group Facade
 * @group GetUrlCollectionFacadeTest
 * Add your own group annotations below this line
 */
class GetUrlCollectionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Url\UrlBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetUrlCollectionFiltersUrlsByLocale(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $urlTransfer = $this->tester->haveUrl([UrlTransfer::FK_LOCALE => $localeTransfer->getIdLocale()]);

        $urlCriteriaTransfer = (new UrlCriteriaTransfer())->setUrlConditions(
            (new UrlConditionsTransfer())
                ->addIdLocale($localeTransfer->getIdLocale()),
        );

        // Act
        $urlCollectionTransfer = $this->tester->getFacade()
            ->getUrlCollection($urlCriteriaTransfer);

        // Assert
        $this->assertCount(1, $urlCollectionTransfer->getUrls());
        $this->assertSame($urlCollectionTransfer->getUrls()->offsetGet(0)->getFkLocale(), $urlTransfer->getFkLocale());
    }

    /**
     * @return void
     */
    public function testGetUrlCollectionPaginatesUrls(): void
    {
        // Arrange
        $this->tester->haveUrl();
        $this->tester->haveUrl();
        $this->tester->haveUrl();

        $urlCriteriaTransfer = (new UrlCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())
                    ->setLimit(2)
                    ->setOffset(0),
            );

        // Act
        $urlCollectionTransfer = $this->tester->getFacade()
            ->getUrlCollection($urlCriteriaTransfer);

        // Assert
        $this->assertCount(2, $urlCollectionTransfer->getUrls());
    }

    /**
     * @return void
     */
    public function testGetUrlCollectionSortsUrls(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $this->tester->haveUrl([UrlTransfer::URL => 'abc', UrlTransfer::FK_LOCALE => $localeTransfer->getIdLocale()]);
        $this->tester->haveUrl([UrlTransfer::URL => 'bac', UrlTransfer::FK_LOCALE => $localeTransfer->getIdLocale()]);
        $this->tester->haveUrl([UrlTransfer::URL => 'cab', UrlTransfer::FK_LOCALE => $localeTransfer->getIdLocale()]);

        $urlCriteriaTransfer = (new UrlCriteriaTransfer())->setUrlConditions(
            (new UrlConditionsTransfer())
                ->addIdLocale($localeTransfer->getIdLocale()),
        )
            ->addSort(
                (new SortTransfer())->setField(UrlTransfer::URL)
                    ->setIsAscending(false),
            );

        // Act
        $urlCollectionTransfer = $this->tester->getFacade()
            ->getUrlCollection($urlCriteriaTransfer);

        // Assert
        $this->assertCount(3, $urlCollectionTransfer->getUrls());
        $this->assertSame($urlCollectionTransfer->getUrls()->offsetGet(0)->getUrl(), 'cab');
        $this->assertSame($urlCollectionTransfer->getUrls()->offsetGet(1)->getUrl(), 'bac');
        $this->assertSame($urlCollectionTransfer->getUrls()->offsetGet(2)->getUrl(), 'abc');
    }
}

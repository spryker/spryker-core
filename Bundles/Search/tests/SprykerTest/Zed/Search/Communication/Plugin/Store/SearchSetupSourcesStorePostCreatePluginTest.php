<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Communication\Plugin\Store;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Psr\Log\NullLogger;
use Spryker\Zed\Search\Business\SearchFacade;
use Spryker\Zed\Search\Communication\Plugin\Store\SearchSetupSourcesStorePostCreatePlugin;
use SprykerTest\Zed\Search\SearchCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Communication
 * @group Plugin
 * @group Store
 * @group SearchSetupSourcesStorePostCreatePluginTest
 * Add your own group annotations below this line
 */
class SearchSetupSourcesStorePostCreatePluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Search\SearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteReturnsStoreResponseTransfer(): void
    {
        //Arrange
        $searchSetupSourcesStorePostCreatePlugin = new SearchSetupSourcesStorePostCreatePlugin();
        $facadeMock = $this->createMock(SearchFacade::class);
        $facadeMock->method('installSources')->with(new NullLogger(), SearchCommunicationTester::STORE);
        $storeTransfer = new StoreTransfer();
        $storeTransfer->setName(SearchCommunicationTester::STORE);

        $searchSetupSourcesStorePostCreatePlugin->setFacade($facadeMock);
        //Act
        $storeResponseTransfer = $searchSetupSourcesStorePostCreatePlugin->execute($storeTransfer);

        //Assert
        $this->assertEquals($storeTransfer, $storeResponseTransfer->getStore());
        $this->assertTrue($storeResponseTransfer->getIsSuccessful());
    }
}

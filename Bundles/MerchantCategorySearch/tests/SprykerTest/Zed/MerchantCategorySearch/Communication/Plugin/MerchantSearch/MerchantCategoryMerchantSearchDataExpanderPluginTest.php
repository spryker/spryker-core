<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryBuilder;
use Generated\Shared\Transfer\MerchantCategoryResponseTransfer;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchTransfer;
use Spryker\Zed\MerchantCategorySearch\Communication\MerchantCategorySearchCommunicationFactory;
use Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch\MerchantCategoryMerchantSearchDataExpanderPlugin;
use Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeInterface;
use Spryker\Zed\MerchantSearchExtension\Dependency\Plugin\MerchantSearchDataExpanderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCategorySearch
 * @group Communication
 * @group Plugin
 * @group MerchantSearch
 * @group MerchantCategoryMerchantSearchDataExpanderPluginTest
 * Add your own group annotations below this line
 */
class MerchantCategoryMerchantSearchDataExpanderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch\MerchantCategoryMerchantSearchDataExpanderPlugin::ID_MERCHANT
     */
    protected const ID_MERCHANT = 'id_merchant';

    /**
     * @uses \Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch\MerchantCategoryMerchantSearchDataExpanderPlugin::CATEGORY_IDS
     */
    protected const CATEGORY_KEYS = 'category-keys';

    /**
     * @uses \Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch\MerchantCategoryMerchantSearchDataExpanderPlugin::SEARCH_RESULT_DATA
     */
    protected const SEARCH_RESULT_DATA = 'search-result-data';

    protected const CATEGORY_TRANSFER_COUNT = 3;

    /**
     * @var \SprykerTest\Zed\MerchantCategorySearch\MerchantCategorySearchCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandSuccess(): void
    {
        // Arrange
        $plugin = $this->getMerchantCategoryMerchantSearchDataExpanderPlugin();
        $factoryMock = $this->getFactoryMock();
        $facadeMock = $this->getMerchantCategorySearchToMerchantCategoryFacadeBuilderMock();

        $factoryMock->method('getMerchantCategoryFacade')
            ->willReturn($facadeMock);

        $merchantCategoryResponseTransfer = new MerchantCategoryResponseTransfer();
        $categoryTransferKeys = $this->addCategoriesToMerchantCategoryResponseTransfer(
            $merchantCategoryResponseTransfer,
            static::CATEGORY_TRANSFER_COUNT
        );

        $facadeMock->method('get')
            ->willReturn($merchantCategoryResponseTransfer);

        $merchantSearchCollectionTransfer = new MerchantSearchCollectionTransfer();
        $merchantSearchTransfer = new MerchantSearchTransfer();
        $merchantSearchTransfer->setData([
            static::SEARCH_RESULT_DATA => [static::ID_MERCHANT => 1],
        ]);
        $merchantSearchCollectionTransfer->addMerchant($merchantSearchTransfer);
        $plugin->setFactory($factoryMock);

        // Act
        $resultMerchantSearchData = $plugin->expand($merchantSearchCollectionTransfer);

        // Assert
        $this->assertCount(1, $resultMerchantSearchData->getMerchants());

        $this->assertSame(
            [
                static::SEARCH_RESULT_DATA => [
                    static::ID_MERCHANT => 1,
                ],
                static::CATEGORY_KEYS => $categoryTransferKeys,
            ],
            $resultMerchantSearchData->getMerchants()[0]->getData()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSearchExtension\Dependency\Plugin\MerchantSearchDataExpanderPluginInterface
     */
    protected function getMerchantCategoryMerchantSearchDataExpanderPlugin(): MerchantSearchDataExpanderPluginInterface
    {
        return new MerchantCategoryMerchantSearchDataExpanderPlugin();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantCategorySearch\Communication\MerchantCategorySearchCommunicationFactory
     */
    protected function getFactoryMock(): MerchantCategorySearchCommunicationFactory
    {
        return $this->getMockBuilder(MerchantCategorySearchCommunicationFactory::class)
            ->setMethods(['getMerchantCategoryFacade'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeInterface
     */
    protected function getMerchantCategorySearchToMerchantCategoryFacadeBuilderMock(): MerchantCategorySearchToMerchantCategoryFacadeInterface
    {
        return $this->getMockBuilder(MerchantCategorySearchToMerchantCategoryFacadeInterface::class)
            ->setMethods(['get'])
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCategoryResponseTransfer $merchantCategoryResponseTransfer
     * @param int $categoryTransferCount
     *
     * @return int[]
     */
    protected function addCategoriesToMerchantCategoryResponseTransfer(
        MerchantCategoryResponseTransfer $merchantCategoryResponseTransfer,
        int $categoryTransferCount
    ): array {
        $categoryTransferKeys = [];
        while ($categoryTransferCount--) {
            $categoryTransfer = (new CategoryBuilder())->build();
            $merchantCategoryResponseTransfer->addCategory($categoryTransfer);
            $categoryTransferKeys[] = $categoryTransfer->getCategoryKey();
        }

        return $categoryTransferKeys;
    }
}

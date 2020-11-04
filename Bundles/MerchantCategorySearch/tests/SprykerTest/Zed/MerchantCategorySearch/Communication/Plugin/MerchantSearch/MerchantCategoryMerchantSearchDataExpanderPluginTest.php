<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\MerchantCategoryResponseTransfer;
use Generated\Shared\Transfer\MerchantCategoryTransfer;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchTransfer;
use Spryker\Zed\MerchantCategorySearch\Communication\MerchantCategorySearchCommunicationFactory;
use Spryker\Zed\MerchantCategorySearch\Communication\Plugin\MerchantSearch\MerchantCategoryMerchantSearchDataExpanderPlugin;
use Spryker\Zed\MerchantCategorySearch\Dependency\Facade\MerchantCategorySearchToMerchantCategoryFacadeInterface;

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
     * @uses \Spryker\Zed\MerchantCategorySearch\Communication\Expander\MerchantCategorySearchExpander::CATEGORY_KEYS
     */
    protected const CATEGORY_KEYS = 'category-keys';

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
        $expectedCategoryKey = 'example';

        $facadeMock = $this->getMerchantCategoryFacadeMock();
        $facadeMock->method('get')
            ->willReturn(
                (new MerchantCategoryResponseTransfer())
                    ->addMerchantCategory(
                        (new MerchantCategoryTransfer())
                            ->setCategory(
                                (new CategoryTransfer())
                                    ->setCategoryKey($expectedCategoryKey)
                            )
                    )
            );

        $factoryMock = $this->getFactoryMock();
        $factoryMock->method('getMerchantCategoryFacade')
            ->willReturn($facadeMock);

        $plugin = new MerchantCategoryMerchantSearchDataExpanderPlugin();
        $plugin->setFactory($factoryMock);

        $merchantSearchCollectionTransfer = (new MerchantSearchCollectionTransfer())
            ->addMerchant(new MerchantSearchTransfer());

        // Act
        $expandedMerchantSearchCollectionTransfer = $plugin->expand($merchantSearchCollectionTransfer);

        // Assert
        $this->assertNotEmpty($expandedMerchantSearchCollectionTransfer->getMerchants());
        $this->assertSame(
            [
                static::CATEGORY_KEYS => [$expectedCategoryKey],
            ],
            $expandedMerchantSearchCollectionTransfer->getMerchants()[0]->getData()
        );
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
    protected function getMerchantCategoryFacadeMock(): MerchantCategorySearchToMerchantCategoryFacadeInterface
    {
        return $this->getMockBuilder(MerchantCategorySearchToMerchantCategoryFacadeInterface::class)
            ->setMethods(['get'])
            ->getMock();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProductCountsTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface;
use SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester;
use Twig\Environment;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantPortalGui
 * @group Communication
 * @group DataProvider
 * @group ProductsDashboardCardDataProviderTest
 * Add your own group annotations below this line
 */
class ProductsDashboardCardDataProviderTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductsDashboardCardDataProvider::CARD_TITLE
     *
     * @var string
     */
    protected const CARD_TITLE = 'Products';

    /**
     * @var string
     */
    protected const CARD_CONTENT = 'Product card content';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductsDashboardCardDataProvider::TITLE_TEMPLATE
     *
     * @var string
     */
    protected const TITLE_TEMPLATE = '@ProductMerchantPortalGui/Partials/dashboard/products_card_title.twig';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductsDashboardCardDataProvider::CONTENT_TEMPLATE
     *
     * @var string
     */
    protected const CONTENT_TEMPLATE = '@ProductMerchantPortalGui/Partials/dashboard/products_card_content.twig';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductsDashboardCardDataProvider::LABEL_MANAGE_PRODUCTS
     *
     * @var string
     */
    protected const LABEL_MANAGE_PRODUCTS = 'Manage Products';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\ProductsDashboardCardDataProvider::URL_PRODUCTS
     *
     * @var string
     */
    protected const URL_PRODUCTS = '/product-merchant-portal-gui/products';

    /**
     * @var \SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester
     */
    protected ProductMerchantPortalGuiCommunicationTester $tester;

    /**
     * @dataProvider getProductsCardPositiveDataProvider
     *
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     * @param \Generated\Shared\Transfer\MerchantProductCountsTransfer $merchantProductCountsTransfer
     *
     * @return void
     */
    public function testGetProductsCardReturnsCorrectMerchantDashboardCardTransfer(
        MerchantUserTransfer $merchantUserTransfer,
        MerchantProductCountsTransfer $merchantProductCountsTransfer
    ): void {
        // Arrange
        $this->tester->mockFactoryMethod(
            'getRepository',
            $this->createRepositoryMock($merchantProductCountsTransfer),
        );
        $this->tester->mockFactoryMethod(
            'getMerchantUserFacade',
            $this->createMerchantUserFacadeMock($merchantUserTransfer),
        );
        $this->tester->mockFactoryMethod(
            'getTwigEnvironment',
            $this->createTwigMock(),
        );

        $productsDashboardCardDataProvider = $this->tester->getFactory()->createProductsDashboardCardDataProvider();

        // Act
        $merchantDashboardCardTransfer = $productsDashboardCardDataProvider->getProductsCard();

        // Assert
        $this->assertSame(static::CARD_TITLE, $merchantDashboardCardTransfer->getTitle());
        $this->assertSame(static::CARD_CONTENT, $merchantDashboardCardTransfer->getContent());

        /** @var \ArrayObject<\Generated\Shared\Transfer\MerchantDashboardActionButtonTransfer> $actionButtons */
        $actionButtons = $merchantDashboardCardTransfer->getActionButtons();
        $this->assertCount(1, $actionButtons);

        /** @var \Generated\Shared\Transfer\MerchantDashboardActionButtonTransfer $actionButton */
        $actionButton = $actionButtons->offsetGet(0);
        $this->assertSame(static::LABEL_MANAGE_PRODUCTS, $actionButton->getTitle());
        $this->assertSame(static::URL_PRODUCTS, $actionButton->getUrl());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantUserTransfer $merchantUserTransfer
     *
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected function createMerchantUserFacadeMock(
        MerchantUserTransfer $merchantUserTransfer
    ): ProductMerchantPortalGuiToMerchantUserFacadeInterface {
        $merchantUserFacadeMock = $this->createMock(ProductMerchantPortalGuiToMerchantUserFacadeInterface::class);
        $merchantUserFacadeMock->method('getCurrentMerchantUser')
            ->willReturn($merchantUserTransfer);

        return $merchantUserFacadeMock;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCountsTransfer $merchantProductCountsTransfer
     *
     * @return \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface
     */
    protected function createRepositoryMock(
        MerchantProductCountsTransfer $merchantProductCountsTransfer
    ): ProductMerchantPortalGuiRepositoryInterface {
        $repositoryMock = $this->createMock(ProductMerchantPortalGuiRepositoryInterface::class);
        $repositoryMock->method('getProductsDashboardCardCounts')
            ->willReturn($merchantProductCountsTransfer);

        return $repositoryMock;
    }

    /**
     * @return \Twig\Environment
     */
    protected function createTwigMock(): Environment
    {
        $twigEnvironmentMock = $this->createMock(Environment::class);
        $twigEnvironmentMock->method('render')
            ->willReturnCallback(function (string $template): string {
                if ($template === static::TITLE_TEMPLATE) {
                    return static::CARD_TITLE;
                }

                if ($template === static::CONTENT_TEMPLATE) {
                    return static::CARD_CONTENT;
                }

                return '';
            });

        return $twigEnvironmentMock;
    }

    /**
     * @return array<string, array<\Generated\Shared\Transfer\MerchantProductCountsTransfer>>
     */
    public static function getProductsCardPositiveDataProvider(): array
    {
        return [
            'Zero products' => [
                (new MerchantUserTransfer())
                    ->setIdMerchant(1),
                (new MerchantProductCountsTransfer())
                    ->setTotal(0)
                    ->setActive(0)
                    ->setInactive(0)
                    ->setLowInStock(0)
                    ->setExpiring(0),
            ],
            'Standard product counts' => [
                (new MerchantUserTransfer())
                    ->setIdMerchant(2),
                (new MerchantProductCountsTransfer())
                    ->setTotal(10)
                    ->setActive(5)
                    ->setInactive(5)
                    ->setLowInStock(2)
                    ->setExpiring(1),
            ],
            'Max numbers of products' => [
                (new MerchantUserTransfer())
                    ->setIdMerchant(3),
                (new MerchantProductCountsTransfer())
                    ->setTotal(PHP_INT_MAX)
                    ->setActive(PHP_INT_MAX)
                    ->setInactive(PHP_INT_MAX)
                    ->setLowInStock(PHP_INT_MAX)
                    ->setExpiring(PHP_INT_MAX),
            ],
        ];
    }
}

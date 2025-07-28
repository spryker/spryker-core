<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Table;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountTableCriteriaTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepository;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;
use SprykerTest\Zed\Discount\DiscountCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Table
 * @group DiscountsTableTest
 * Add your own group annotations below this line
 */
class DiscountsTableTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\Discount\DiscountCommunicationTester
     */
    protected DiscountCommunicationTester $tester;

    /**
     * @var array<string, array<int>>
     */
    protected array $discountsDataProviderData = [];

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransferDE;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransferAT;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $this->discountsDataProviderData = $this->discountsDataProviderData();
    }

    /**
     * @dataProvider discountsDataProvider
     *
     * @param string $dataKey
     * @param array $discountTableCriteriaTransferData
     *
     * @return void
     */
    public function testApplyCriteria(string $dataKey, array $discountTableCriteriaTransferData = []): void
    {
        // Arrange
        $expectedDiscountIds = $this->discountsDataProviderData[$dataKey];
        if (isset($discountTableCriteriaTransferData[DiscountTableCriteriaTransfer::STORES])) {
            $discountTableCriteriaTransferData[DiscountTableCriteriaTransfer::STORES] = [$this->storeTransferAT->getIdStore()];
        }
        $discountTableCriteriaTransfer = $this->tester->createDiscountTableCriteriaTransfer($discountTableCriteriaTransferData);
        $discountsTable = $this->createProductTableMock();
        $discountsTable->applyCriteria($discountTableCriteriaTransfer);

        // Act
        $resultData = $discountsTable->fetchData();

        // Assert
        $resultDiscounts = $resultData['data'];
        $discountIds = array_column($resultDiscounts, 0);
        $diff = array_diff($expectedDiscountIds, $discountIds);
        $this->assertEmpty($diff);
    }

    /**
     * @return \SprykerTest\Zed\Discount\Communication\Table\DiscountsTableMock
     */
    protected function createProductTableMock(): DiscountsTableMock
    {
        return new DiscountsTableMock(
            $this->createDiscountQueryContainer()->queryDiscount(),
            $this->createDiscountQueryContainer(),
            $this->getFactory()->getCalculatorPlugins(),
            $this->createDiscountRepository(),
        );
    }

    /**
     * @return array<string, array>
     */
    protected function discountsDataProvider(): array
    {
        return [
            'Filter by Status' => [
                'Filter by Status',
                [
                    DiscountTableCriteriaTransfer::STATUS => 1,
                ],
            ],
            'Filter by Types' => [
                'Filter by Types',
                [
                    DiscountTableCriteriaTransfer::TYPES => [DiscountConstants::TYPE_VOUCHER],
                ],
            ],
            'Filter by Stores' => [
                'Filter by Stores',
                [
                    DiscountTableCriteriaTransfer::STORES => ['AT'],
                ],
            ],
            'Filter by Dates' => [
                'Filter by Dates',
                [
                    DiscountTableCriteriaTransfer::VALID_FROM => '2023-12-31 23:59:59',
                    DiscountTableCriteriaTransfer::VALID_TO => '2025-01-01 00:00:00',
                ],
            ],
            'Filter by Different Fields' => [
                'Different Fields',
                [
                    DiscountTableCriteriaTransfer::STATUS => 0,
                    DiscountTableCriteriaTransfer::TYPES => [DiscountConstants::TYPE_CART_RULE],
                    DiscountTableCriteriaTransfer::VALID_FROM => '2023-12-31 23:59:59',
                    DiscountTableCriteriaTransfer::VALID_TO => '2025-01-01 00:00:00',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array>
     */
    protected function discountsDataProviderData(): array
    {
        $voucherDiscountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
            DiscountTransfer::IS_ACTIVE => true,
            DiscountTransfer::VALID_FROM => '2023-12-31 23:59:59',
            DiscountTransfer::VALID_TO => '2027-01-01 00:00:00',
        ]);
        $voucherDiscountTransfer = (new DiscountTransfer())->fromArray($voucherDiscountGeneralTransfer->toArray(), true);
        $this->tester->haveDiscountStore($this->storeTransferDE, $voucherDiscountTransfer);
        $this->tester->haveDiscountStore($this->storeTransferAT, $voucherDiscountTransfer);

        $cartRuleDiscountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_CART_RULE,
            DiscountTransfer::IS_ACTIVE => true,
            DiscountTransfer::STORE_RELATION => ['DE'],
            DiscountTransfer::VALID_FROM => '2023-12-31 23:59:59',
            DiscountTransfer::VALID_TO => '2027-01-01 00:00:00',
        ]);
        $voucherInActiveDiscountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
            DiscountTransfer::IS_ACTIVE => false,
            DiscountTransfer::STORE_RELATION => ['DE'],
            DiscountTransfer::VALID_FROM => '2023-12-31 23:59:59',
            DiscountTransfer::VALID_TO => '2027-01-01 00:00:00',
        ]);
        $cartRuleInActiveDiscountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_CART_RULE,
            DiscountTransfer::IS_ACTIVE => false,
            DiscountTransfer::STORE_RELATION => ['DE'],
            DiscountTransfer::VALID_FROM => '2023-12-31 23:59:59',
            DiscountTransfer::VALID_TO => '2027-01-01 00:00:00',
        ]);

        return [
            'Filter by Status' => [$voucherDiscountGeneralTransfer->getIdDiscount(), $cartRuleDiscountGeneralTransfer->getIdDiscount()],
            'Filter by Types' => [$voucherDiscountGeneralTransfer->getIdDiscount(), $voucherInActiveDiscountGeneralTransfer->getIdDiscount()],
            'Filter by Stores' => [$voucherDiscountGeneralTransfer->getIdDiscount()],
            'Filter by Dates' => [$voucherDiscountGeneralTransfer->getIdDiscount()],
            'Different Fields' => [$cartRuleInActiveDiscountGeneralTransfer->getIdDiscount()],
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected function createDiscountQueryContainer(): DiscountQueryContainerInterface
    {
        return new DiscountQueryContainer();
    }

    /**
     * @return \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected function createDiscountRepository(): DiscountRepositoryInterface
    {
        return new DiscountRepository();
    }

    /**
     * @return \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory
     */
    protected function getFactory(): DiscountCommunicationFactory
    {
        return $this->tester->getFactory();
    }
}

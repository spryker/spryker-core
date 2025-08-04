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
        $storeTransferDE = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransferAT = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_AT]);
        $expectedDiscountIds = $this->discountsDataProviderData($storeTransferDE, $storeTransferAT)[$dataKey];
        if (isset($discountTableCriteriaTransferData[DiscountTableCriteriaTransfer::STORES])) {
            $discountTableCriteriaTransferData[DiscountTableCriteriaTransfer::STORES] = [$storeTransferAT->getIdStore()];
        }
        $discountTableCriteriaTransfer = $this->tester->createDiscountTableCriteriaTransfer($discountTableCriteriaTransferData);
        $discountsTableMock = $this->createDiscountsTableMock();

        // Act
        $discountsTableMock->applyCriteria($discountTableCriteriaTransfer);
        $resultData = $discountsTableMock->fetchData();

        // Assert
        $resultDiscounts = $resultData['data'];
        $discountIds = array_column($resultDiscounts, 0);
        $diff = array_diff($expectedDiscountIds, $discountIds);
        $this->assertEmpty($diff);
    }

    /**
     * @return \SprykerTest\Zed\Discount\Communication\Table\DiscountsTableMock
     */
    protected function createDiscountsTableMock(): DiscountsTableMock
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
                    DiscountTableCriteriaTransfer::STORES => [static::STORE_NAME_AT],
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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransferDE
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransferAT
     *
     * @return array<string, array>
     */
    protected function discountsDataProviderData(StoreTransfer $storeTransferDE, StoreTransfer $storeTransferAT): array
    {
        $voucherDiscountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
            DiscountTransfer::IS_ACTIVE => true,
            DiscountTransfer::VALID_FROM => '2023-12-31 23:59:59',
            DiscountTransfer::VALID_TO => '2027-01-01 00:00:00',
        ]);
        $voucherDiscountTransfer = (new DiscountTransfer())->fromArray($voucherDiscountGeneralTransfer->toArray(), true);
        $this->tester->haveDiscountStore($storeTransferDE, $voucherDiscountTransfer);
        $this->tester->haveDiscountStore($storeTransferAT, $voucherDiscountTransfer);

        $cartRuleDiscountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_CART_RULE,
            DiscountTransfer::IS_ACTIVE => true,
            DiscountTransfer::STORE_RELATION => [static::STORE_NAME_DE],
            DiscountTransfer::VALID_FROM => '2023-12-31 23:59:59',
            DiscountTransfer::VALID_TO => '2027-01-01 00:00:00',
        ]);
        $voucherInActiveDiscountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_VOUCHER,
            DiscountTransfer::IS_ACTIVE => false,
            DiscountTransfer::STORE_RELATION => [static::STORE_NAME_DE],
            DiscountTransfer::VALID_FROM => '2023-12-31 23:59:59',
            DiscountTransfer::VALID_TO => '2027-01-01 00:00:00',
        ]);
        $cartRuleInActiveDiscountGeneralTransfer = $this->tester->haveDiscount([
            DiscountTransfer::DISCOUNT_TYPE => DiscountConstants::TYPE_CART_RULE,
            DiscountTransfer::IS_ACTIVE => false,
            DiscountTransfer::STORE_RELATION => [static::STORE_NAME_DE],
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

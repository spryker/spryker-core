<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DiscountTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class VoucherFormDataProvider
{

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var array
     */
    private $calculatorPlugins;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(DiscountQueryContainerInterface $discountQueryContainer, array $calculatorPlugins)
    {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->calculatorPlugins = $calculatorPlugins;
    }

    /**
     * @param bool $isMultiple
     *
     * @return array
     */
    public function getData($isMultiple = false)
    {
        return [
            VoucherForm::FIELD_QUANTITY => ($isMultiple ? VoucherForm::MINIMUM_VOUCHERS_TO_GENERATE : VoucherForm::ONE_VOUCHER),
        ];
    }

    /**
     * @param bool $isMultiple
     *
     * @return array
     */
    public function getOptions($isMultiple = false)
    {
        return [
            VoucherForm::OPTION_DISCOUNT_VOUCHER_POOL_CHOICES => $this->getPoolChoices(),
            VoucherForm::OPTION_CODE_LENGTH_CHOICES => $this->getCodeLengthChoices(),
            VoucherForm::OPTION_IS_MULTIPLE => $isMultiple,
        ];
    }

    /**
     * @return array
     */
    protected function getPoolChoices()
    {
        $pools = [];
        $poolResult = $this->discountQueryContainer->queryVoucherPool()->find();

        if (!empty($poolResult)) {
            foreach ($poolResult as $discountVoucherPoolEntity) {
                $pools[$discountVoucherPoolEntity->getIdDiscountVoucherPool()] = $this->getDiscountVoucherPoolDisplayName($discountVoucherPoolEntity);
            }
        }

        return $pools;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $discountVoucherPoolEntity
     *
     * @return string
     */
    protected function getDiscountVoucherPoolDisplayName(SpyDiscountVoucherPool $discountVoucherPoolEntity)
    {
        $displayName = $discountVoucherPoolEntity->getName();

        $discounts = [];
        foreach ($discountVoucherPoolEntity->getDiscounts() as $discountEntity) {
            $discountTransfer = new DiscountTransfer();
            $discountTransfer->fromArray($discountEntity->toArray(), true);

            /** @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface $calculator */
            $calculator = $this->calculatorPlugins[$discountEntity->getCalculatorPlugin()];

            $discounts[] = $calculator->getFormattedAmount($discountTransfer);
        }

        if (!empty($discounts)) {
            $displayName .= ' (' . implode(', ', $discounts) . ')';
        }

        return $displayName;
    }

    /**
     * @return array
     */
    protected function getCodeLengthChoices()
    {
        $codeLengthChoices = [
            0 => 'No additional random characters',
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
        ];

        return $codeLengthChoices;
    }

}

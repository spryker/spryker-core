<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Form\DataProvider;

use Generated\Shared\Transfer\DiscountTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Zed\Discount\Communication\Form\VoucherForm;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;

class VoucherFormDataProvider
{

    /**
     * @var DiscountQueryContainer
     */
    protected $discountQueryContainer;

    /**
     * @var DiscountConfig
     */
    protected $discountConfig;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainer $discountQueryContainer
     * @param \Spryker\Zed\Discount\DiscountConfig $discountConfig
     */
    public function __construct(DiscountQueryContainer $discountQueryContainer, DiscountConfig $discountConfig)
    {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountConfig = $discountConfig;
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
        $availableCalculatorPlugins = $this->discountConfig->getAvailableCalculatorPlugins();
        $displayName = $discountVoucherPoolEntity->getName();

        $discounts = [];
        foreach ($discountVoucherPoolEntity->getDiscounts() as $discountEntity) {
            $discountTransfer = new DiscountTransfer();
            $discountTransfer->fromArray($discountEntity->toArray(), true);

            /* @var DiscountCalculatorPluginInterface $calculator */
            $calculator = $availableCalculatorPlugins[$discountEntity->getCalculatorPlugin()];

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

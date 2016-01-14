<?php

namespace Spryker\Zed\Discount\Communication\Table;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolCategoryTableMap;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class VoucherPoolTable extends AbstractTable
{

    const COL_OPTIONS = 'options';
    const COL_CATEGORY_NAME = 'category_name';
    const COL_VOUCHERS_COUNT = 'Vouchers';
    const COL_AMOUNT = 'amount';

    const URL_DISCOUNT_POOL_EDIT = '/discount/pool/edit';
    const URL_DISCOUNT_VOUCHER_VIEW = '/discount/voucher/view';
    const PARAM_ID_POOL = 'id-pool';
    const CONTROLLER_TABLE_ACTION = 'poolTable';

    const DATE_FORMAT = 'Y-m-d';
    const SPACE_SEPARATOR = ' ';

    /**
     * @var SpyDiscountVoucherPoolQuery
     */
    protected $poolQuery;

    /**
     * @var DiscountConfig
     */
    protected $discountConfig;

    /**
     * @param SpyDiscountVoucherPoolQuery $discountVoucherPool
     * @param DiscountConfig $discountConfig
     */
    public function __construct(SpyDiscountVoucherPoolQuery $discountVoucherPool, DiscountConfig $discountConfig)
    {
        $this->poolQuery = $discountVoucherPool;
        $this->discountConfig = $discountConfig;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl(self::CONTROLLER_TABLE_ACTION);

        $config->setHeader([
            SpyDiscountVoucherPoolTableMap::COL_CREATED_AT => 'Date Created',
            SpyDiscountVoucherPoolTableMap::COL_NAME => 'Voucher Name',
            self::COL_CATEGORY_NAME => 'Category Name',
            self::COL_AMOUNT => 'Amount',
            self::COL_VOUCHERS_COUNT => 'Codes',
            self::COL_OPTIONS => 'Options',
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $results = [];

        $query = $this->poolQuery
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, 'category_name')
            ->withColumn('COUNT(' . SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL . ')', self::COL_VOUCHERS_COUNT)
            ->useDiscountVoucherQuery()
            ->endUse()
            ->useVoucherPoolCategoryQuery()
            ->endUse()
            ->groupByIdDiscountVoucherPool();

        $queryResults = $this->runQuery($query, $config, true);

        /** @var SpyDiscountVoucherPool $discountVoucherPool */
        foreach ($queryResults as $discountVoucherPool) {
            $categoryName = null;
            if ($discountVoucherPool->getVoucherPoolCategory() !== null) {
                $categoryName = $discountVoucherPool->getVoucherPoolCategory()->getName();
            }
            $results[] = [
                SpyDiscountVoucherPoolTableMap::COL_CREATED_AT => $discountVoucherPool->getCreatedAt(self::DATE_FORMAT),
                SpyDiscountVoucherPoolTableMap::COL_NAME => $discountVoucherPool->getName(),
                self::COL_CATEGORY_NAME => $categoryName,
                self::COL_AMOUNT => $this->getDiscountVoucherPoolDisplayName($discountVoucherPool),
                self::COL_VOUCHERS_COUNT => $discountVoucherPool->getDiscountVouchers()->count(),
                self::COL_OPTIONS => $this->createRowOptions($discountVoucherPool),
            ];
        }

        return $results;
    }

    /**
     * @param SpyDiscountVoucherPool $discountVoucherPool
     *
     * @return string
     */
    protected function getEditUrl(SpyDiscountVoucherPool $discountVoucherPool)
    {
        return Url::generate(
            DiscountConstants::URL_DISCOUNT_POOL_EDIT,
            [
                DiscountConstants::PARAM_ID_POOL => $discountVoucherPool->getIdDiscountVoucherPool(),
            ]
        );
    }

    /**
     * @param SpyDiscountVoucherPool $discountVoucherPool
     *
     * @return string
     */
    protected function getViewUrl(SpyDiscountVoucherPool $discountVoucherPool)
    {
        return Url::generate(
            self::URL_DISCOUNT_VOUCHER_VIEW,
            [
                self::PARAM_ID_POOL => $discountVoucherPool->getIdDiscountVoucherPool(),
            ]
        );
    }

    /**
     * @param SpyDiscountVoucherPool $discountVoucherPool
     *
     * @return string
     */
    protected function createRowOptions(SpyDiscountVoucherPool $discountVoucherPool)
    {
        return $this->generateEditButton($this->getEditUrl($discountVoucherPool), 'Edit Voucher')
            . self::SPACE_SEPARATOR
            . $this->generateViewButton($this->getViewUrl($discountVoucherPool), 'View Codes')
            . self::SPACE_SEPARATOR
            . $this->generateCreateButton('/discount/voucher/create-single', 'Add Single Code')
            . self::SPACE_SEPARATOR
            . $this->generateCreateButton('/discount/voucher/create-multiple', 'Add Multiple Codes');
    }

    /**
     * @param SpyDiscountVoucherPool $discountVoucherPoolEntity
     *
     * @return string|null
     */
    protected function getDiscountVoucherPoolDisplayName(SpyDiscountVoucherPool $discountVoucherPoolEntity)
    {
        $availableCalculatorPlugins = $this->discountConfig->getAvailableCalculatorPlugins();
        $displayName = null;

        $discounts = [];
        foreach ($discountVoucherPoolEntity->getDiscounts() as $discountEntity) {
            $discountTransfer = new DiscountTransfer();
            $discountTransfer->fromArray($discountEntity->toArray(), true);

            /* @var DiscountCalculatorPluginInterface $calculator */
            $calculator = $availableCalculatorPlugins[$discountEntity->getCalculatorPlugin()];

            $discounts[] = $calculator->getFormattedAmount($discountTransfer);
        }

        if (!empty($discounts)) {
            $displayName = implode(', ', $discounts);
        }

        return $displayName;
    }

}

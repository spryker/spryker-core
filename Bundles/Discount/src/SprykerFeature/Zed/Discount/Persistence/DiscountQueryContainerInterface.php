<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategoryQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery;

/**
 * Class DiscountQueryContainer
 */
interface DiscountQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param string $code
     *
     * @return SpyDiscountVoucherQuery
     */
    public function queryVoucher($code);

    /**
     * @param int $idDiscount
     *
     * @return SpyDiscountDecisionRuleQuery
     */
    public function queryDecisionRules($idDiscount);

    /**
     * @return SpyDiscountQuery
     */
    public function queryActiveAndRunningDiscounts();

    /**
     * @return SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool();

    /**
     * @return SpyDiscountQuery
     */
    public function queryDiscount();

    /**
     * @return SpyDiscountDecisionRuleQuery
     */
    public function queryDiscountDecisionRule();

    /**
     * @return SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher();

    /**
     * @return SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool();

    /**
     * @return SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPoolJoinedVoucherPoolCategory();

    /**
     * @return SpyDiscountVoucherPoolCategoryQuery
     */
    public function queryDiscountVoucherPoolCategory();

    /**
     * @param array|string[] $couponCodes
     *
     * @return SpyDiscountQuery
     */
    public function queryCartRulesIncludingSpecifiedVouchers(array $couponCodes = []);

    /**
     * @param array $codes
     *
     * @return SpyDiscountVoucherQuery
     */
    public function queryVoucherPoolByVoucherCodes(array $codes);

}

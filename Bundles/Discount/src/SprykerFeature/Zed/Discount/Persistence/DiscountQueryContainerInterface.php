<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery;

/**
 * Class DiscountQueryContainer
 */
interface DiscountQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param string $code
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery
     */
    public function queryVoucher($code);

    /**
     * @param int $idDiscount
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery
     */
    public function queryDecisionRules($idDiscount);

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery
     */
    public function queryActiveAndRunningDiscounts();

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery
     */
    public function queryVoucherPool();

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery
     */
    public function queryDiscount();

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRuleQuery
     */
    public function queryDiscountDecisionRule();

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery
     */
    public function queryDiscountVoucher();

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPool();

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery
     */
    public function queryDiscountVoucherPoolJoinedVoucherPoolCategory();

    /**
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategoryQuery
     */
    public function queryDiscountVoucherPoolCategory();

    /**
     * @param array|string[] $couponCodes
     *
     * @return \SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery
     */
    public function queryCartRulesIncludingSpecifiedVouchers(array $couponCodes = []);

    /**
     * @param array $codes
     *
     * @return SpyDiscountVoucherQuery
     */
    public function queryVoucherPoolByVoucherCodes(array $codes);
}

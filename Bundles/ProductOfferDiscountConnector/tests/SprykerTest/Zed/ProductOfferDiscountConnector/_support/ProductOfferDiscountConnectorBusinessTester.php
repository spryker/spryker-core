<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferDiscountConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ClauseTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferDiscountConnectorBusinessTester extends Actor
{
    use _generated\ProductOfferDiscountConnectorBusinessTesterActions;

    /**
     * @uses \Spryker\Zed\ProductOfferDiscountConnector\Communication\Plugin\Discount\ProductOfferReferenceDecisionRulePlugin::FIELD_NAME_PRODUCT_OFFER_REFERENCE
     *
     * @var string
     */
    protected const FIELD_NAME_PRODUCT_OFFER_REFERENCE = 'product-offer-reference';

    /**
     * @uses \Spryker\Zed\ProductOfferDiscountConnector\Communication\Plugin\Discount\ProductOfferReferenceDecisionRulePlugin::TYPE_STRING
     *
     * @var string
     */
    protected const TYPE_STRING = 'string';

    /**
     * @param string $operator
     * @param string $value
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    public function createClauseTransfer(string $operator, string $value): ClauseTransfer
    {
        return (new ClauseTransfer())
            ->setOperator($operator)
            ->setField(static::FIELD_NAME_PRODUCT_OFFER_REFERENCE)
            ->setValue($value)
            ->setAcceptedTypes([static::TYPE_STRING]);
    }
}

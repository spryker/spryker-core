<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\CustomerDiscountConnector;

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
class CustomerDiscountConnectorCommunicationTester extends Actor
{
    use _generated\CustomerDiscountConnectorCommunicationTesterActions;

    /**
     * @uses \Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Discount\CustomerReferenceDecisionRulePlugin::FIELD_NAME_CUSTOMER_REFERENCE
     *
     * @var string
     */
    protected const FIELD_NAME_CUSTOMER_REFERENCE = 'customer-reference';

    /**
     * @uses \Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Discount\CustomerReferenceDecisionRulePlugin::TYPE_STRING
     *
     * @var string
     */
    protected const TYPE_STRING = 'string';

    /**
     * @uses \Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Discount\CustomerOrderAmountDecisionRulePlugin::FIELD_NAME_MAX_USES_PER_CUSTOMER
     *
     * @var string
     */
    protected const FIELD_NAME_MAX_USES_PER_CUSTOMER = 'max-uses-per-customer';

    /**
     * @uses \Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Discount\CustomerOrderAmountDecisionRulePlugin::TYPE_NUMBER
     *
     * @var string
     */
    protected const TYPE_NUMBER = 'number';

    /**
     * @param string $operator
     * @param string $value
     * @param array<string, mixed> $metadata
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    public function createClauseTransfer(string $operator, string $value, array $metadata = []): ClauseTransfer
    {
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer
            ->setOperator($operator)
            ->setValue($value);

        if ($metadata) {
            $clauseTransfer->setMetadata($metadata);
        }

        if (is_numeric($value)) {
            $clauseTransfer
                ->setField(static::FIELD_NAME_MAX_USES_PER_CUSTOMER)
                ->setAcceptedTypes([static::TYPE_NUMBER]);
        } else {
            $clauseTransfer
                ->setField(static::FIELD_NAME_CUSTOMER_REFERENCE)
                ->setAcceptedTypes([static::TYPE_STRING]);
        }

        return $clauseTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;

abstract class AbstractQueryStringMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface
{
    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidator::GLOSSARY_KEY_RULE_ENGINE_VALIDATION_INVALID_QUERY_STRING
     *
     * @var string
     */
    protected const GLOSSARY_KEY_RULE_ENGINE_VALIDATION_INVALID_QUERY_STRING = 'rule_engine.validation.invalid_query_string';

    /**
     * @uses \Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidator::GLOSSARY_KEY_RULE_ENGINE_VALIDATION_INVALID_COMPARE_OPERATOR_VALUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_RULE_ENGINE_VALIDATION_INVALID_COMPARE_OPERATOR_VALUE = 'rule_engine.validation.invalid_compare_operator_value';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_QUERY_STRING = 'merchant_commission.validation.invalid_query_string';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_COMPARE_OPERATOR = 'merchant_commission.validation.invalid_compare_operator';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_FIELD = '%field%';

    /**
     * @var array<string, string>
     */
    protected const GLOSSARY_KEY_MAP = [
        self::GLOSSARY_KEY_RULE_ENGINE_VALIDATION_INVALID_QUERY_STRING => self::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_QUERY_STRING,
        self::GLOSSARY_KEY_RULE_ENGINE_VALIDATION_INVALID_COMPARE_OPERATOR_VALUE => self::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_INVALID_COMPARE_OPERATOR,
    ];

    /**
     * @return string
     */
    abstract protected function getFieldName(): string;

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $ruleEngineErrorTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function translateRuleEngineValidationErrors(ArrayObject $ruleEngineErrorTransfers): ArrayObject
    {
        $errorTransfers = new ArrayObject();
        foreach ($ruleEngineErrorTransfers as $ruleEngineErrorTransfer) {
            $messageGlossaryKey = $ruleEngineErrorTransfer->getMessageOrFail();
            if (!isset(static::GLOSSARY_KEY_MAP[$messageGlossaryKey])) {
                $errorTransfers->append($ruleEngineErrorTransfer);

                continue;
            }

            $errorTransfer = (new ErrorTransfer())
                ->setEntityIdentifier($ruleEngineErrorTransfer->getEntityIdentifierOrFail())
                ->setMessage(static::GLOSSARY_KEY_MAP[$messageGlossaryKey])
                ->setParameters([
                    static::GLOSSARY_KEY_PARAMETER_FIELD => $this->getFieldName(),
                ]);
            $errorTransfers->append($errorTransfer);
        }

        return $errorTransfers;
    }
}

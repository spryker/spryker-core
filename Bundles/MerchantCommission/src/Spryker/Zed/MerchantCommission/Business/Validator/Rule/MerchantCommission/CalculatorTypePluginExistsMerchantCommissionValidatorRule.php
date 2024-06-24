<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class CalculatorTypePluginExistsMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CALCULATOR_TYPE_PLUGIN_MISSING = 'merchant_commission.validation.calculator_type_plugin_is_missing';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_CALCULATOR_TYPE = '%calculator_type%';

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var list<\Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface>
     */
    protected array $merchantCommissionCalculatorTypePlugins;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param list<\Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface> $merchantCommissionCalculatorTypePlugins
     */
    public function __construct(
        ErrorAdderInterface $errorAdder,
        array $merchantCommissionCalculatorTypePlugins
    ) {
        $this->errorAdder = $errorAdder;
        $this->merchantCommissionCalculatorTypePlugins = $merchantCommissionCalculatorTypePlugins;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantCommissionTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $availableCalculatorPluginTypes = $this->getAvailableCalculatorPluginTypes();
        $calculatorPluginTypesIndexedByEntityIdentifier = $this->getCalculatorPluginTypesIndexedByEntityIdentifier(
            $merchantCommissionTransfers,
        );

        $missingCalculatorPluginTypes = array_diff($calculatorPluginTypesIndexedByEntityIdentifier, $availableCalculatorPluginTypes);
        foreach ($missingCalculatorPluginTypes as $entityIdentifier => $calculatorPluginType) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_CALCULATOR_TYPE_PLUGIN_MISSING,
                [static::GLOSSARY_KEY_PARAMETER_CALCULATOR_TYPE => $calculatorPluginType],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @return list<string>
     */
    protected function getAvailableCalculatorPluginTypes(): array
    {
        $calculatorPluginTypes = [];
        foreach ($this->merchantCommissionCalculatorTypePlugins as $merchantCommissionCalculatorTypePlugin) {
            $calculatorPluginTypes[] = strtolower($merchantCommissionCalculatorTypePlugin->getCalculatorType());
        }

        return $calculatorPluginTypes;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<string|int, string>
     */
    protected function getCalculatorPluginTypesIndexedByEntityIdentifier(ArrayObject $merchantCommissionTransfers): array
    {
        $indexedCalculatorTypePlugins = [];
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            $indexedCalculatorTypePlugins[$entityIdentifier] = strtolower($merchantCommissionTransfer->getCalculatorTypePluginOrFail());
        }

        return $indexedCalculatorTypePlugins;
    }
}

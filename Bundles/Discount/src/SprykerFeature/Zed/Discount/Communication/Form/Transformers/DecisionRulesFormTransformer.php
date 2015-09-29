<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Form\Transformers;

use SprykerFeature\Zed\Discount\Communication\Form\VoucherCodesType;
use Symfony\Component\Form\DataTransformerInterface;
use Zend\Filter\Word\CamelCaseToUnderscore;

class DecisionRulesFormTransformer implements DataTransformerInterface
{
    /**
     * @var CamelCaseToUnderscore
     */
    protected $camelCaseToUnderscoreFilter;

    /**
     * @param CamelCaseToUnderscore $camelCaseToUnderscoreFilter
     */
    public function __construct(CamelCaseToUnderscore $camelCaseToUnderscoreFilter)
    {
        $this->camelCaseToUnderscoreFilter = $camelCaseToUnderscoreFilter;
    }

    /**
     * @param array $formArray
     *
     * @return array
     */
    public function transform($formArray)
    {

        foreach ($formArray[VoucherCodesType::FIELD_DECISION_RULES] as $index => $fieldValue) {
            $fixedValueSet = [];
            foreach ($fieldValue as $key => $value) {
                $fixedValueSet[$this->camelCaseToSnakeCase($key)] = $value;
            }

            $formArray[VoucherCodesType::FIELD_DECISION_RULES][$index] = $fixedValueSet;
        }
        
        return $formArray;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function reverseTransform($value)
    {
        return $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function camelCaseToSnakeCase($value)
    {
        $value = $this->camelCaseToUnderscoreFilter->filter($value);

        return $this->lowerCaseString($value);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function lowerCaseString($value)
    {
        return mb_convert_case($value, MB_CASE_LOWER, 'UTF-8');
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\QueryBuilderTransformer;

use Spryker\Zed\Discount\Business\DiscountFacade;

class JavascriptQueryBuilderTransformer
{

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacade
     */
    protected $discountFacade;

    /**
     * @var string[]
     */
    protected $queryOperatorMapping = [
        '=' => 'equal',
        '!=' => 'not_equal',
        '<' => 'less',
        '<=' => 'less_or_equal',
        '>' => 'greater',
        '>=' => 'greater_or_equal',
        'does not contain' => 'not_contains',
        'is in' => 'in',
        'is not in' => 'not_in',
    ];

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacade $discountFacade
     */
    public function __construct(DiscountFacade $discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param string $type
     *
     * @return string[]
     */
    public function getFilters($type)
    {
        $ruleFields = $this->discountFacade->getQueryStringFieldsByType($type);
        $valueOptions = $this->discountFacade->getQueryStringValueOptions($type);

        $transformed = [];
        foreach ($ruleFields as $ruleField) {

            $fieldTransformed = [];
            $fieldTransformed['id'] = $ruleField;
            $fieldTransformed['label'] = $ruleField;
            $fieldTransformed['type'] = 'string';
            $fieldTransformed['operators'] = $this->transformComparators($type, $ruleField);

            if ($valueOptions) {
                $fieldTransformed['input'] = 'select';
                $fieldTransformed['values'] = $valueOptions;
            }

            $transformed[] = $fieldTransformed;
        }

        return $transformed;
    }

    /**
     * @param string $type
     * @param string $ruleField
     *
     * @return string[]
     */
    protected function transformComparators($type, $ruleField)
    {
        $comparators = $this->discountFacade
            ->getQueryStringFieldExpressionsForField(
                $type,
                $ruleField
            );

        foreach ($comparators as $key => $comparator) {
            $comparators[$key] = preg_replace('/\s/i', '_', $comparator);

            if (isset($this->queryOperatorMapping[$comparator])) {
                $comparators[$key] = $this->queryOperatorMapping[$comparator];
            }
        }

        return $comparators;
    }

}

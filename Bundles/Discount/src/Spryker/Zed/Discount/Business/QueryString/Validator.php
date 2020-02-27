<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\Exception\QueryBuilderException;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;

class Validator implements ValidatorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface
     */
    protected $decisionRuleBuilder;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface
     */
    protected $collectorBuilder;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface $decisionRuleBuilder
     * @param \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilderInterface $collectorBuilder
     */
    public function __construct(
        SpecificationBuilderInterface $decisionRuleBuilder,
        SpecificationBuilderInterface $collectorBuilder
    ) {
        $this->decisionRuleBuilder = $decisionRuleBuilder;
        $this->collectorBuilder = $collectorBuilder;
    }

    /**
     * @param string $type
     * @param string $queryString
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryBuilderException
     *
     * @return string[]
     */
    public function validateByType($type, $queryString)
    {
        $validationMessages = [];
        try {
            switch (strtolower($type)) {
                case MetaProviderFactory::TYPE_DECISION_RULE:
                    $this->decisionRuleQueryString($queryString);

                    break;
                case MetaProviderFactory::TYPE_COLLECTOR:
                    $this->collectorQueryString($queryString);

                    break;
                default:
                    throw new QueryBuilderException(
                        sprintf(
                            'Meta data provider with name "%s" is not provided for configuration in validator class. ',
                            $type
                        )
                    );
            }
        } catch (ComparatorException $e) {
            $validationMessages[] = $e->getMessage();
        } catch (QueryStringException $e) {
            $validationMessages[] = $e->getMessage();
        }

        return $validationMessages;
    }

    /**
     * Dry run for decision rules
     *
     * @param string $queryString
     *
     * @return void
     */
    protected function decisionRuleQueryString($queryString)
    {
        $this->decisionRuleBuilder->buildFromQueryString($queryString);
    }

    /**
     * Dry run for collectors
     *
     * @param string $queryString
     *
     * @return void
     */
    protected function collectorQueryString($queryString)
    {
        $this->collectorBuilder->buildFromQueryString($queryString);
    }
}

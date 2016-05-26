<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Assertion\Business\Exception\InvalidArgumentException;
use Spryker\Zed\Discount\Business\Exception\ComparatorException;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;

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
     * @return array|string[]
     */
    public function validateByType($type, $queryString)
    {
        $validationMessages = [];
        try {
            switch (strtolower($type)) {
                case SpecificationBuilder::TYPE_DECISION_RULE:
                    $this->decisionRuleQueryString($queryString);
                    break;

                case SpecificationBuilder::TYPE_COLLECTOR:
                    $this->collectorQueryString($queryString);
                    break;

                default:
                    throw new \InvalidArgumentException(
                        sprintf(
                            'Invalid type "%s" for validation request.',
                            $type
                        )
                    );
            }
        } catch (ComparatorException $e) {
            $validationMessages[] = $e->getMessage();
        } catch (QueryStringException $e) {
            $validationMessages[] = $e->getMessage();
        } catch (InvalidArgumentException $e) {
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
        $collectorComposite = $this->decisionRuleBuilder->buildFromQueryString($queryString);
        $collectorComposite->isSatisfiedBy(new QuoteTransfer(), new ItemTransfer());
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
        $collectorComposite = $this->collectorBuilder->buildFromQueryString($queryString);
        $collectorComposite->collect(new QuoteTransfer());
    }

}

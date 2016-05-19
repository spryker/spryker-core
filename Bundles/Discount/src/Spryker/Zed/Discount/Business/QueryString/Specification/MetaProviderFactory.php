<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification;

use Spryker\Zed\Discount\Business\DiscountBusinessFactory;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;

class MetaProviderFactory
{
    /**
     * @var DiscountBusinessFactory
     */
    protected $discountBusinessFactory;

    /**
     * @param DiscountBusinessFactory $discountBusinessFactory
     */
    public function __construct(DiscountBusinessFactory $discountBusinessFactory)
    {
        $this->discountBusinessFactory = $discountBusinessFactory;
    }

    /**
     * @param $type
     *
     * @return MetaDataProvider
     */
    public function createMetaProviderByType($type)
    {
        switch (strtolower($type)) {
            case SpecificationBuilder::TYPE_DECISION_RULE:
                return $this->createDecisionRuleMetaProvider();
                break;
            case SpecificationBuilder::TYPE_COLLECTOR:
                return $this->createCollectorMetaProvider();
                break;

        }

        throw new \InvalidArgumentException(
            sprintf(
                'Meta provider for type "%s" not found.',
                $type
            )
        );
    }

    /**
     * @return MetaDataProvider
     */
    protected function createDecisionRuleMetaProvider()
    {
        return new MetaDataProvider(
            $this->discountBusinessFactory->getDecisionRulePlugins(),
            $this->discountBusinessFactory->createComparatorOperators(),
            $this->discountBusinessFactory->createLogicalComparators()
        );
    }

    /**
     * @return MetaDataProvider
     */
    protected function createCollectorMetaProvider()
    {
        return new MetaDataProvider(
            $this->discountBusinessFactory->getCollectorPlugins(),
            $this->discountBusinessFactory->createComparatorOperators(),
            $this->discountBusinessFactory->createLogicalComparators()
        );
    }
}

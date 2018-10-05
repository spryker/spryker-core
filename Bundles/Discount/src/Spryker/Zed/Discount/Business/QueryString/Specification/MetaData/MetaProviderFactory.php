<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\MetaData;

use Spryker\Zed\Discount\Business\DiscountBusinessFactory;
use Spryker\Zed\Discount\Business\Exception\QueryBuilderException;

class MetaProviderFactory implements MetaProviderFactoryInterface
{
    public const TYPE_COLLECTOR = 'collector';
    public const TYPE_DECISION_RULE = 'decision-rule';

    /**
     * @var \Spryker\Zed\Discount\Business\DiscountBusinessFactory
     */
    protected $discountBusinessFactory;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountBusinessFactory $discountBusinessFactory
     */
    public function __construct(DiscountBusinessFactory $discountBusinessFactory)
    {
        $this->discountBusinessFactory = $discountBusinessFactory;
    }

    /**
     * @param string $type
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryBuilderException
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
     */
    public function createMetaProviderByType($type)
    {
        switch (strtolower($type)) {
            case self::TYPE_DECISION_RULE:
                return $this->createDecisionRuleMetaProvider();
            case self::TYPE_COLLECTOR:
                return $this->createCollectorMetaProvider();
        }

        throw new QueryBuilderException(
            sprintf(
                'Meta provider for type "%s" not found.',
                $type
            )
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
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
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
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

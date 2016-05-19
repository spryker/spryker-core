<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class CollectorProvider implements SpecificationProviderInterface
{

    /**
     * @var CollectorPluginInterface[]
     */
    protected $collectorPlugins = [];

    /**
     * @param CollectorPluginInterface[] $collectorPlugins
     */
    public function __construct(array $collectorPlugins)
    {
        $this->collectorPlugins = $collectorPlugins;
    }

    /**
     * @param CollectorSpecificationInterface $left
     * @param CollectorSpecificationInterface $right
     *
     * @return CollectorAndSpecification
     */
    public function createAnd($left, $right)
    {
        return new CollectorAndSpecification($left, $right);
    }

    /**
     * @param CollectorSpecificationInterface $left
     * @param CollectorSpecificationInterface $right
     *
     * @return CollectorAndSpecification
     */
    public function createOr($left, $right)
    {
        return new CollectorOrSpecification($left, $right);
    }

    /**
     * @param ClauseTransfer $clauseTransfer
     *
     * @return CollectorSpecificationInterface
     *
     * @throws QueryStringException
     */
    public function getSpecificationContext(ClauseTransfer $clauseTransfer)
    {
        foreach ($this->collectorPlugins as $collectorPlugin) {
            if (strcasecmp($collectorPlugin->getFieldName(), $clauseTransfer->getField()) === 0) {
                return new CollectorContext($collectorPlugin, $clauseTransfer);
            }
        }

        throw new QueryStringException(
            sprintf(
                'Could not find collector plugin for "%s" field. Have you registered it in "%s::getCollectorPlugins" plugins stack?',
                $clauseTransfer->getField(),
                DiscountDependencyProvider::class
            )
        );
    }
}

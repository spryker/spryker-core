<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainerInterface;

abstract class AbstractWriter
{

    /**
     * @var DiscountQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param DiscountQueryContainerInterface $queryContainer
     */
    public function __construct(DiscountQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @return DiscountQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

}

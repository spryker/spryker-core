<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Writer;

use Spryker\Zed\Discount\Persistence\DiscountQueryContainer;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

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
     * @return \Spryker\Zed\Discount\Persistence\DiscountQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

}

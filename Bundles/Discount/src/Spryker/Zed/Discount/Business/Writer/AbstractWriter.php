<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Writer;

use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

abstract class AbstractWriter
{

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $queryContainer
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

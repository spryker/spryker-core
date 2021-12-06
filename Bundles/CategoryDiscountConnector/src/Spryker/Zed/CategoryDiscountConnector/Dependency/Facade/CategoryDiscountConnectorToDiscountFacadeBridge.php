<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Dependency\Facade;

use Generated\Shared\Transfer\ClauseTransfer;

class CategoryDiscountConnectorToDiscountFacadeBridge implements CategoryDiscountConnectorToDiscountFacadeInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\DiscountFacadeInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountFacadeInterface $discountFacade
     */
    public function __construct($discountFacade)
    {
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     * @param string $compareWith
     *
     * @return bool
     */
    public function queryStringCompare(ClauseTransfer $clauseTransfer, $compareWith)
    {
        return $this->discountFacade->queryStringCompare($clauseTransfer, $compareWith);
    }
}

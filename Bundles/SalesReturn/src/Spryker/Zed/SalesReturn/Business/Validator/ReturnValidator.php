<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Validator;

use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface;

class ReturnValidator implements ReturnValidatorInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface $salesFacade
     */
    public function __construct(SalesReturnToSalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validateReturnRequest(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        // TODO: get order items

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

class OrderCustomReferenceGuiToOrderCustomReferenceBridge implements OrderCustomReferenceGuiToOrderCustomReferenceInterface
{
    /**
     * @var \Spryker\Zed\OrderCustomReference\Business\OrderCustomReferenceFacadeInterface
     */
    protected $orderCustomReferenceFacade;

    /**
     * @param \Spryker\Zed\OrderCustomReference\Business\OrderCustomReferenceFacadeInterface $orderCustomReferenceFacade
     */
    public function __construct($orderCustomReferenceFacade)
    {
        $this->orderCustomReferenceFacade = $orderCustomReferenceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderCustomReference(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->orderCustomReferenceFacade->saveOrderCustomReference($quoteTransfer, $saveOrderTransfer);
    }
}

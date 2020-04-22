<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class OrderCustomReferenceGuiToOrderCustomReferenceFacadeBridge implements OrderCustomReferenceGuiToOrderCustomReferenceFacadeInterface
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
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer
     */
    public function updateOrderCustomReference(
        string $orderCustomReference,
        OrderTransfer $orderTransfer
    ): OrderCustomReferenceResponseTransfer {
        return $this->orderCustomReferenceFacade->updateOrderCustomReference($orderCustomReference, $orderTransfer);
    }
}

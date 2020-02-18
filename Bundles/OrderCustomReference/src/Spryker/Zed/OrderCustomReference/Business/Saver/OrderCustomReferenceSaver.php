<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business\Saver;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\OrderCustomReference\Business\Validator\OrderCustomReferenceValidatorInterface;
use Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface;

class OrderCustomReferenceSaver implements OrderCustomReferenceSaverInterface
{
    /**
     * @var \Spryker\Zed\OrderCustomReference\Business\Validator\OrderCustomReferenceValidatorInterface
     */
    protected $orderCustomReferenceValidator;

    /**
     * @var \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface
     */
    protected $orderCustomReferenceEntityManager;

    /**
     * @param \Spryker\Zed\OrderCustomReference\Business\Validator\OrderCustomReferenceValidatorInterface $orderCustomReferenceValidator
     * @param \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface $orderCustomReferenceEntityManager
     */
    public function __construct(
        OrderCustomReferenceValidatorInterface $orderCustomReferenceValidator,
        OrderCustomReferenceEntityManagerInterface $orderCustomReferenceEntityManager
    ) {
        $this->orderCustomReferenceValidator = $orderCustomReferenceValidator;
        $this->orderCustomReferenceEntityManager = $orderCustomReferenceEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderCustomReference(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $saveOrderTransfer->requireIdSalesOrder();

        $isOrderCustomReferenceLengthValid = $this->orderCustomReferenceValidator
            ->isOrderCustomReferenceLengthValid($quoteTransfer->getOrderCustomReference());

        if (!$isOrderCustomReferenceLengthValid) {
            return;
        }

        $this->orderCustomReferenceEntityManager->saveOrderCustomReference($quoteTransfer, $saveOrderTransfer);
    }
}

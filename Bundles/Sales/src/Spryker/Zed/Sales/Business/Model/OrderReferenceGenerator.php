<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface;

class OrderReferenceGenerator implements OrderReferenceGeneratorInterface
{

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface
     */
    protected $sequenceNumberFacade;

    /**
     * @var \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    protected $sequenceNumberSettings;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberInterface $sequenceNumberFacade
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer $sequenceNumberSettingsTransfer
     */
    public function __construct(
        SalesToSequenceNumberInterface $sequenceNumberFacade,
        SequenceNumberSettingsTransfer $sequenceNumberSettingsTransfer
    ) {
        $this->sequenceNumberFacade = $sequenceNumberFacade;
        $this->sequenceNumberSettings = $sequenceNumberSettingsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function generateOrderReference(OrderTransfer $orderTransfer)
    {
        return $this->sequenceNumberFacade->generate($this->sequenceNumberSettings);
    }

}

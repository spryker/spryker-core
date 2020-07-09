<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Dependency\Facade;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;

class SalesInvoiceToSequenceNumberFacadeBridge implements SalesInvoiceToSequenceNumberFacadeInterface
{
    /**
     * @var \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    protected $sequenceNumberFacade;

    /**
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     */
    public function __construct($sequenceNumberFacade)
    {
        $this->sequenceNumberFacade = $sequenceNumberFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer $sequenceNumberSettings
     *
     * @return string
     */
    public function generate(SequenceNumberSettingsTransfer $sequenceNumberSettings)
    {
        return $this->sequenceNumberFacade->generate($sequenceNumberSettings);
    }
}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateOrderReference(QuoteTransfer $quoteTransfer)
    {
        return $this->sequenceNumberFacade->generate($this->sequenceNumberSettings);
    }

}

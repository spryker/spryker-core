<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToSequenceNumberInterface;

class QuoteRequestReferenceGenerator implements QuoteRequestReferenceGeneratorInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToSequenceNumberInterface
     */
    protected $facadeSequenceNumber;

    /**
     * @var \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    protected $sequenceNumberSettings;

    /**
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToSequenceNumberInterface $sequenceNumberFacade
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer $sequenceNumberSettings
     */
    public function __construct(
        QuoteRequestToSequenceNumberInterface $sequenceNumberFacade,
        SequenceNumberSettingsTransfer $sequenceNumberSettings
    ) {
        $this->facadeSequenceNumber = $sequenceNumberFacade;
        $this->sequenceNumberSettings = $sequenceNumberSettings;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return string
     */
    public function generateQuoteRequestReference(QuoteRequestTransfer $quoteRequestTransfer): string
    {
        return $this->facadeSequenceNumber->generate($this->sequenceNumberSettings);
    }
}

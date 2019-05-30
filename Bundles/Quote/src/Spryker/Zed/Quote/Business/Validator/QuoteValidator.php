<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Validator;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;

class QuoteValidator implements QuoteValidatorInterface
{
    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatorPluginInterface[]
     */
    protected $quoteValidatorPlugins;

    /**
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatorPluginInterface[] $quoteValidatorPlugins
     */
    public function __construct(array $quoteValidatorPlugins)
    {
        $this->quoteValidatorPlugins = $quoteValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(true);
        $quoteValidationResponseTransfer = $this->executeQuoteValidatorPlugins($quoteTransfer, $quoteValidationResponseTransfer);

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function executeQuoteValidatorPlugins(
        QuoteTransfer $quoteTransfer,
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer
    ): QuoteValidationResponseTransfer {
        foreach ($this->quoteValidatorPlugins as $quoteValidatorPlugin) {
            $quoteValidationResponseTransferFromPlugin = $quoteValidatorPlugin->validate($quoteTransfer);

            if (!$quoteValidationResponseTransferFromPlugin->getIsSuccessful()) {
                foreach ($quoteValidationResponseTransferFromPlugin->getErrors() as $quoteErrorTransfer) {
                    $quoteValidationResponseTransfer->addErrors($quoteErrorTransfer);
                }
                $quoteValidationResponseTransfer->setIsSuccessful(false);
            }
        }

        return $quoteValidationResponseTransfer;
    }
}

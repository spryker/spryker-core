<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\QuoteConfig;

class QuoteOperation implements QuoteOperationInterface
{
    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteFieldsAllowedForSavingProviderPluginInterface[]
     */
    protected $getQuoteFieldsPlugins;

    /**
     * @var \Spryker\Zed\Quote\QuoteConfig
     */
    protected $quoteConfig;

    /**
     * @param \Spryker\Zed\Quote\QuoteConfig $quoteConfig
     * @param array $getQuoteFieldsPlugins
     */
    public function __construct(
        QuoteConfig $quoteConfig,
        array $getQuoteFieldsPlugins = []
    ) {
        $this->quoteConfig = $quoteConfig;
        $this->getQuoteFieldsPlugins = $getQuoteFieldsPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        $quoteFields = array_merge($this->quoteConfig->getQuoteFieldsAllowedForSaving(), $this->executeQuoteFieldsPlugins($quoteTransfer));

        return array_unique($quoteFields);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function executeQuoteFieldsPlugins(QuoteTransfer $quoteTransfer): array
    {
        $quoteFields = [];
        foreach ($this->getQuoteFieldsPlugins as $quoteFieldsPlugin) {
            $quoteFields = array_merge($quoteFieldsPlugin->execute($quoteTransfer));
        }

        return $quoteFields;
    }
}

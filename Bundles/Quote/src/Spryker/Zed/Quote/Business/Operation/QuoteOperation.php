<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Operation;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Quote\QuoteConfig;

class QuoteOperation implements QuoteOperationInterface
{
    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteFieldsAllowedForSavingProviderPluginInterface[]
     */
    protected $quoteFieldsAllowedForSavingProviderPlugins;

    /**
     * @var \Spryker\Zed\Quote\QuoteConfig
     */
    protected $quoteConfig;

    /**
     * @param \Spryker\Zed\Quote\QuoteConfig $quoteConfig
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteFieldsAllowedForSavingProviderPluginInterface[] $quoteFieldsAllowedForSavingProviderPlugins
     */
    public function __construct(
        QuoteConfig $quoteConfig,
        array $quoteFieldsAllowedForSavingProviderPlugins = []
    ) {
        $this->quoteConfig = $quoteConfig;
        $this->quoteFieldsAllowedForSavingProviderPlugins = $quoteFieldsAllowedForSavingProviderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getQuoteFieldsAllowedForSaving(QuoteTransfer $quoteTransfer): array
    {
        $quoteFields = array_merge($this->quoteConfig->getQuoteFieldsAllowedForSaving(), $this->executeQuoteFieldsAllowedForSavingProviderPlugins($quoteTransfer));

        return array_unique($quoteFields);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function executeQuoteFieldsAllowedForSavingProviderPlugins(QuoteTransfer $quoteTransfer): array
    {
        $quoteFieldsAllowedForSaving = [];
        foreach ($this->quoteFieldsAllowedForSavingProviderPlugins as $quoteFieldsAllowedForSavingProviderPlugin) {
            $quoteFieldsAllowedForSaving[] = $quoteFieldsAllowedForSavingProviderPlugin->execute($quoteTransfer);
        }

        return array_merge(...$quoteFieldsAllowedForSaving);
    }
}

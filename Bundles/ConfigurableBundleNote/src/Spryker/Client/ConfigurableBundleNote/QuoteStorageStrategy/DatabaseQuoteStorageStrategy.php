<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleNote\Zed\ConfigurableBundleNoteZedStubInterface;

class DatabaseQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     */
    protected const STORAGE_STRATEGY = 'database';

    /**
     * @var \Spryker\Client\ConfigurableBundleNote\Zed\ConfigurableBundleNoteZedStubInterface
     */
    protected $configurableBundleNoteZedStub;

    /**
     * @param \Spryker\Client\ConfigurableBundleNote\Zed\ConfigurableBundleNoteZedStubInterface $configurableBundleNoteZedStub
     */
    public function __construct(ConfigurableBundleNoteZedStubInterface $configurableBundleNoteZedStub)
    {
        $this->configurableBundleNoteZedStub = $configurableBundleNoteZedStub;
    }

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer {
        return $this->configurableBundleNoteZedStub->setConfiguredBundleNote($configuredBundleNoteRequestTransfer);
    }
}

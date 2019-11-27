<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\QuoteStorageStrategy;

use Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ConfigurableBundleCartNote\Zed\ConfigurableBundleCartNoteZedStubInterface;

class DatabaseQuoteStorageStrategy implements QuoteStorageStrategyInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     */
    protected const STORAGE_STRATEGY = 'database';

    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\Zed\ConfigurableBundleCartNoteZedStubInterface
     */
    protected $configurableBundleCartNoteZedStub;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\Zed\ConfigurableBundleCartNoteZedStubInterface $configurableBundleCartNoteZedStub
     */
    public function __construct(ConfigurableBundleCartNoteZedStubInterface $configurableBundleCartNoteZedStub)
    {
        $this->configurableBundleCartNoteZedStub = $configurableBundleCartNoteZedStub;
    }

    /**
     * @return string
     */
    public function getStorageStrategy(): string
    {
        return static::STORAGE_STRATEGY;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleCartNote(
        ConfiguredBundleCartNoteRequestTransfer $configuredBundleCartNoteRequestTransfer
    ): QuoteResponseTransfer {
        return $this->configurableBundleCartNoteZedStub->setConfiguredBundleCartNote($configuredBundleCartNoteRequestTransfer);
    }
}

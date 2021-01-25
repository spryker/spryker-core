<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleNote;

use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ConfigurableBundleNote\ConfigurableBundleNoteFactory getFactory()
 */
class ConfigurableBundleNoteClient extends AbstractClient implements ConfigurableBundleNoteClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @throws \Spryker\Client\ConfigurableBundleNote\Exception\QuoteStorageStrategyNotFound
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer {
        return $this->getFactory()
            ->getQuoteStorageStrategy()
            ->setConfiguredBundleNote($configuredBundleNoteRequestTransfer);
    }
}

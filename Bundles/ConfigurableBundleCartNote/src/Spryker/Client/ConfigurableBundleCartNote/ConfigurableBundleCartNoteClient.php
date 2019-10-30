<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ConfigurableBundleCartNote\ConfigurableBundleCartNoteFactory getFactory()
 */
class ConfigurableBundleCartNoteClient extends AbstractClient implements ConfigurableBundleCartNoteClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $note
     * @param string $configurableBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setCartNoteToConfigurableBundle(string $note, string $configurableBundleGroupKey): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->getQuoteStorageStrategy()
            ->setCartNoteToConfigurableBundle($note, $configurableBundleGroupKey);
    }
}

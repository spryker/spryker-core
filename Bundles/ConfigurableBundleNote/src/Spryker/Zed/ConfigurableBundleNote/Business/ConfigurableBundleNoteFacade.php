<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleNote\Business;

use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ConfigurableBundleNote\Business\ConfigurableBundleNoteBusinessFactory getFactory()
 */
class ConfigurableBundleNoteFacade extends AbstractFacade implements ConfigurableBundleNoteFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setConfiguredBundleNote(
        ConfiguredBundleNoteRequestTransfer $configuredBundleNoteRequestTransfer
    ): QuoteResponseTransfer {
        return $this->getFactory()
            ->createConfigurableBundleNoteSetter()
            ->setConfiguredBundleNote($configuredBundleNoteRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateCartReorderItemsWithConfigurableBundle(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        return $this->getFactory()
            ->createCartReorderItemHydrator()
            ->hydrate($cartReorderTransfer);
    }
}

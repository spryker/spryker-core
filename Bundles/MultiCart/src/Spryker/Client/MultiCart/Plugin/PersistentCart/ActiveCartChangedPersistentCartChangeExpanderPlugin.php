<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin\PersistentCart;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface;

class ActiveCartChangedPersistentCartChangeExpanderPlugin extends AbstractPlugin implements PersistentCartChangeExpanderPluginInterface
{
    protected const PARAM_ID_QUOTE = 'id_quote';

    /**
     * {@inheritDoc}
     * - Sets `PersistentCartChangeTransfer::isActiveCartChanged` flag to true in case if active cart was changed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function extend(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        if (isset($params[static::PARAM_ID_QUOTE]) && $cartChangeTransfer->getQuote()->getIdQuote() !== $params[static::PARAM_ID_QUOTE]) {
            return $cartChangeTransfer->setIsActiveCartChanged(true);
        }

        return $cartChangeTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\PersistentCartChangeExpanderPluginInterface;

class QuoteSelectorPersistentCartChangeExpanderPlugin implements PersistentCartChangeExpanderPluginInterface
{
    /**
     * @var string
     */
    public const PARAM_ID_QUOTE = 'id_quote';

    /**
     * Specification:
     * - Takes quote id form params and replace it in quote change request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function extend(PersistentCartChangeTransfer $persistentCartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        if (isset($params[static::PARAM_ID_QUOTE])) {
            $persistentCartChangeTransfer->setIdQuote($params[static::PARAM_ID_QUOTE]);
        }

        return $persistentCartChangeTransfer;
    }
}

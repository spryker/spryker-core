<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxProductStorage;

use Generated\Shared\Transfer\TaxProductStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\TaxProductStorage\TaxProductStorageFactory getFactory()
 */
class TaxProductStorageClient extends AbstractClient implements TaxProductStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $productAbstractSku
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer|null
     */
    public function findTaxProductStorageByProductAbstractSku(string $productAbstractSku): ?TaxProductStorageTransfer
    {
        return $this->getFactory()
            ->createTaxProductStorageReader()
            ->findTaxProductStorageByProductAbstractSku($productAbstractSku);
    }
}

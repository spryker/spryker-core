<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\QuickOrder\QuickOrderFactory getFactory()
 */
class QuickOrderClient extends AbstractClient implements QuickOrderClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function expandProductConcreteTransfers(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createProductConcreteExpander()
            ->expand($productConcreteTransfers);
    }
}

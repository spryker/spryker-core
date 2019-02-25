<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Executor;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

class ProductAbstractListTermExecutor implements ProductAbstractListTermExecutorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $parameters
     *
     * @return array
     */
    public function execute(TransferInterface $parameters): array
    {
        /** @var \Generated\Shared\Transfer\ContentProductAbstractListTransfer $contentProductAbstractListTransfer */
        $contentProductAbstractListTransfer = $parameters;

        return $contentProductAbstractListTransfer->getSkus();
    }
}

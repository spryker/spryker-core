<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct\Executor;

use Generated\Shared\Transfer\ExecutedProductAbstractListTransfer;

interface ExecutorProductAbstractListInterface
{
    /**
     * @param \Spryker\Client\ContentProduct\Executor\ContentQueryTransfer $contentQueryTransfer
     *
     * @throws \Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException
     *
     * @return \Spryker\Client\ContentProduct\Executor\ExecutedProductAbstractListTransfer|null
     */
    public function execute(ContentQueryTransfer $contentQueryTransfer): ?ExecutedProductAbstractListTransfer;
}

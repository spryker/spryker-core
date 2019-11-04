<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Writer;

use Generated\Shared\Transfer\SearchContextTransfer;

interface WriterInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function write(array $data, SearchContextTransfer $searchContextTransfer): bool;
}

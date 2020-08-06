<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Index;

use Generated\Shared\Transfer\SearchContextTransfer;

interface SourceIdentifierInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function isSupported(SearchContextTransfer $searchContextTransfer): bool;
}

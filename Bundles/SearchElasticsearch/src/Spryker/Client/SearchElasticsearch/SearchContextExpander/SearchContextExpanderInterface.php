<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\SearchContextExpander;

use Generated\Shared\Transfer\SearchContextTransfer;

interface SearchContextExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function expandSearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Search;

use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextExpanderPluginInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class SearchHttpSearchContextExpanderPlugin extends AbstractPlugin implements SearchContextExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds SearchHttpSearchContextTransfer as a property to SearchContextTransfer.
     * - SearchHttpSearchContextTransfer holds flag defining applicability of Search Adapter Plugin
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function expandSearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        if ($searchContextTransfer->getSearchHttpContext()) {
            $searchContextTransfer->getSearchHttpContext()->setIsApplicable(
                $this->getFactory()->createQueryApplicabilityChecker()->isQueryApplicable($searchContextTransfer),
            );
        }

        return $searchContextTransfer;
    }
}

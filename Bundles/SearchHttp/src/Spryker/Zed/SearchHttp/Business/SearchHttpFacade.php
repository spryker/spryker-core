<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Business;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpRepositoryInterface getRepository()
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SearchHttp\Business\SearchHttpBusinessFactory getFactory()
 */
class SearchHttpFacade extends AbstractFacade implements SearchHttpFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return void
     */
    public function saveSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void
    {
        $this->getEntityManager()->saveSearchHttpConfig($searchHttpConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     *
     * @return void
     */
    public function deleteSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void
    {
        $this->getEntityManager()->deleteSearchHttpConfig($searchHttpConfigTransfer);
    }
}

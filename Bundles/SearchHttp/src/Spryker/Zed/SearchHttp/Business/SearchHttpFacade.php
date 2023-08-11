<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Business;

use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SearchHttp\Business\SearchHttpBusinessFactory getFactory()
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpRepositoryInterface getRepository()
 * @method \Spryker\Zed\SearchHttp\Persistence\SearchHttpEntityManagerInterface getEntityManager()
 */
class SearchHttpFacade extends AbstractFacade implements SearchHttpFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchHttp\Business\SearchHttpFacade::saveSearchHttpConfig()} instead.
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer $searchHttpConfigTransfer
     * @param string $storeReference
     *
     * @return void
     */
    public function publishSearchHttpConfig(
        SearchHttpConfigTransfer $searchHttpConfigTransfer,
        string $storeReference
    ): void {
        $this->getFactory()->createConfigWriter()->write($searchHttpConfigTransfer, $storeReference);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\SearchHttp\Business\SearchHttpFacade::deleteSearchHttpConfig()} instead.
     *
     * @param string $storeReference
     * @param string $applicationId
     *
     * @return void
     */
    public function unpublishSearchHttpConfig(string $storeReference, string $applicationId): void
    {
        $this->getFactory()->createConfigDeleter()->delete($storeReference, $applicationId);
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
    public function saveSearchHttpConfig(SearchHttpConfigTransfer $searchHttpConfigTransfer): void
    {
        $this->getFactory()->createConfigWriter()->saveSearchHttpConfig($searchHttpConfigTransfer);
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
        $this->getFactory()->createConfigDeleter()->deleteSearchHttpConfig($searchHttpConfigTransfer);
    }
}

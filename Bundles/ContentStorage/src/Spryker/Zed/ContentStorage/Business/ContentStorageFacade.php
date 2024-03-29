<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ContentStorage\Business\ContentStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface getRepository()
 */
class ContentStorageFacade extends AbstractFacade implements ContentStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $contentIds
     *
     * @return void
     */
    public function publish(array $contentIds): void
    {
        $this->getFactory()->createContentStorage()->publish($contentIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ContentTransfer>
     */
    public function getContentTransfersByFilter(FilterTransfer $filterTransfer): array
    {
        return $this->getRepository()
            ->getContentTransfersByFilter($filterTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Api\Persistence\ApiPersistenceFactory getFactory()
 */
class ApiQueryContainer extends AbstractQueryContainer implements ApiQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $data)
    {
        return $this->getFactory()
            ->createApiCollectionMapper()
            ->toCollection($data);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param int|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem($data, $id = null)
    {
        return $this->getFactory()
            ->createApiItemMapper()
            ->toItem($data, $id);
    }
}

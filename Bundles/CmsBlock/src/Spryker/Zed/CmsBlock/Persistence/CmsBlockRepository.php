<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockPersistenceFactory getFactory()
 */
class CmsBlockRepository extends AbstractRepository implements CmsBlockRepositoryInterface
{
    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockByName(string $name): ?CmsBlockTransfer
    {
        $cmsBlockEntity = $this->getFactory()->createCmsBlockQuery()->findOneByName($name);

        if (!$cmsBlockEntity) {
            return null;
        }

        $cmsBlockTransfer = $this->getFactory()->createCmsBlockMapper()->mapCmsBlockEntityToTransfer($cmsBlockEntity);

        return $cmsBlockTransfer;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return $this->getFactory()
            ->createCmsBlockQuery()
            ->filterByKey($key)
            ->exists();
    }
}

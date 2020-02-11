<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStoragePersistenceFactory getFactory()
 */
class UrlStorageEntityManager extends AbstractEntityManager implements UrlStorageEntityManagerInterface
{
    /**
     * @param array $urlIds
     *
     * @return void
     */
    public function deleteStorageUrlsByIds(array $urlIds): void
    {
        if (count($urlIds) === 0) {
            return;
        }

        $this->getFactory()
            ->createSpyStorageUrlQuery()
            ->filterByIdUrlStorage_In($urlIds)
            ->delete();
    }
}

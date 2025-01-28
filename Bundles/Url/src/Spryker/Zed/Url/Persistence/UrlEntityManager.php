<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

use Orm\Zed\Url\Persistence\SpyUrl;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Url\Persistence\UrlPersistenceFactory getFactory()
 */
class UrlEntityManager extends AbstractEntityManager implements UrlEntityManagerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     * @param bool|null $isNew
     *
     * @return void
     */
    public function saveUrlEntities(array $urlTransfers, ?bool $isNew = true): void
    {
        $collection = new ObjectCollection();
        $collection->setModel(SpyUrl::class);

        foreach ($urlTransfers as $urlTransfer) {
            $urlEntity = $this->getFactory()->createSpyUrlEntity();
            $urlEntity->fromArray($urlTransfer->toArray());
            $urlEntity->setNew($isNew);
            $collection->append($urlEntity);
        }

        $collection->save();
    }
}

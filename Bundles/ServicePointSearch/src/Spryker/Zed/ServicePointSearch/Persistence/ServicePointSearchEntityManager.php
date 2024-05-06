<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Persistence;

use Generated\Shared\Transfer\ServicePointSearchTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchPersistenceFactory getFactory()
 */
class ServicePointSearchEntityManager extends AbstractEntityManager implements ServicePointSearchEntityManagerInterface
{
    /**
     * @param list<int> $servicePointIds
     *
     * @return void
     */
    public function deleteServicePointSearchByServicePointIds(array $servicePointIds): void
    {
        if (!$servicePointIds) {
            return;
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $servicePointSearchCollection */
        $servicePointSearchCollection = $this->getFactory()
            ->getServicePointSearchPropelQuery()
            ->filterByFkServicePoint_In($servicePointIds)
            ->find();

        $servicePointSearchCollection->delete();
    }

    /**
     * @param list<int> $servicePointSearchIds
     *
     * @return void
     */
    public function deleteServicePointSearchByServicePointSearchIds(array $servicePointSearchIds): void
    {
        if (!$servicePointSearchIds) {
            return;
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $servicePointSearchCollection */
        $servicePointSearchCollection = $this->getFactory()
            ->getServicePointSearchPropelQuery()
            ->filterByIdServicePointSearch_In($servicePointSearchIds)
            ->find();

        $servicePointSearchCollection->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointSearchTransfer $servicePointSearchTransfer
     *
     * @return void
     */
    public function saveServicePointSearch(ServicePointSearchTransfer $servicePointSearchTransfer): void
    {
        $servicePointSearchEntity = $this->getFactory()
            ->getServicePointSearchPropelQuery()
            ->filterByIdServicePointSearch($servicePointSearchTransfer->getIdServicePointSearch())
            ->findOneOrCreate();

        $servicePointSearchEntity->fromArray($servicePointSearchTransfer->toArray());
        $servicePointSearchEntity->setFkServicePoint($servicePointSearchTransfer->getIdServicePointOrFail());

        $servicePointSearchEntity->save();
    }
}

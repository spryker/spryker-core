<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Persistence;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductSet\Persistence\ProductSetPersistenceFactory getFactory()
 */
class ProductSetRepository extends AbstractRepository implements ProductSetRepositoryInterface
{
    /**
     * @param int $idProductSet
     * @param int|null $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findProductSetUrl(int $idProductSet, ?int $idLocale): ?UrlTransfer
    {
        $query = $this->getFactory()
            ->getUrlPropelQuery()
            ->filterByFkResourceProductSet($idProductSet);

        if ($idLocale) {
            $query->filterByFkLocale($idLocale);
        }
        $urlEntity = $query->findOne();

        if ($urlEntity === null) {
            return $urlEntity;
        }

        return $this->getFactory()->createProductSetMapper()->mapUrlEntityToUrlTransfer($urlEntity, new UrlTransfer());
    }
}

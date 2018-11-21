<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Url\Persistence\UrlPersistenceFactory getFactory()
 */
class UrlRepository extends AbstractRepository implements UrlRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrlCaseInsensitive(UrlTransfer $urlTransfer): ?UrlTransfer
    {
        $urlEntity = $this->getFactory()
            ->createUrlQuery()
            ->setIgnoreCase(true)
            ->filterByUrl($urlTransfer->getUrl())
            ->_or()
            ->filterByIdUrl($urlTransfer->getIdUrl())
            ->findOne();

        if ($urlEntity !== null) {
            return (new UrlTransfer())->fromArray($urlEntity->toArray());
        }

        return null;
    }
}

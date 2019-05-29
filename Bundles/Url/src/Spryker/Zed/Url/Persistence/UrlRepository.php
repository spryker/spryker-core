<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
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
        $urlQuery = $this->getFactory()
            ->createUrlQuery()
            ->setIgnoreCase(true);

        if ($urlTransfer->getUrl()) {
            $urlQuery->filterByUrl($urlTransfer->getUrl());
        } else {
            $urlQuery->filterByIdUrl($urlTransfer->getIdUrl());
        }

        $urlEntity = $urlQuery->findOne();

        if ($urlEntity === null) {
            return null;
        }

        return (new UrlTransfer())->fromArray($urlEntity->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param bool $ignoreRedirects
     *
     * @return bool
     */
    public function hasUrlCaseInsensitive(UrlTransfer $urlTransfer, bool $ignoreRedirects): bool
    {
        $urlQuery = $this->getFactory()
            ->createUrlQuery()
            ->setIgnoreCase(true)
            ->_if($ignoreRedirects)
                ->filterByFkResourceRedirect(null, Criteria::ISNULL)
            ->_endif();

        if ($urlTransfer->getUrl()) {
            $urlQuery->filterByUrl($urlTransfer->getUrl());
        } else {
            $urlQuery->filterByIdUrl($urlTransfer->getIdUrl());
        }

        return $urlQuery->exists();
    }
}

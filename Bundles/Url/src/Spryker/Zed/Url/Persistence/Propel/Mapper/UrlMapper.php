<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\UrlCollectionTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Collection\Collection;

class UrlMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Url\Persistence\SpyUrl> $urlEntities
     * @param \Generated\Shared\Transfer\UrlCollectionTransfer $urlCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UrlCollectionTransfer
     */
    public function mapUrlEntitiesToUrlCollectionTransfer(Collection $urlEntities, UrlCollectionTransfer $urlCollectionTransfer): UrlCollectionTransfer
    {
        foreach ($urlEntities as $urlEntity) {
            $urlCollectionTransfer->addUrl(
                (new UrlTransfer())
                    ->fromArray($urlEntity->toArray(), true),
            );
        }

        return $urlCollectionTransfer;
    }
}

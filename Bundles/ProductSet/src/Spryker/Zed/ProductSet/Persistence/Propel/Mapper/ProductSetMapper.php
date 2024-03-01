<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;

class ProductSetMapper
{
    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $spyUrl
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function mapUrlEntityToUrlTransfer(SpyUrl $spyUrl, UrlTransfer $urlTransfer): UrlTransfer
    {
        return $urlTransfer->fromArray($spyUrl->toArray());
    }
}

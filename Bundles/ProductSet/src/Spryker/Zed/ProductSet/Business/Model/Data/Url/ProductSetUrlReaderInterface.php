<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data\Url;

interface ProductSetUrlReaderInterface
{
    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\ProductSet\Business\Exception\ProductSetUrlNotFoundException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function getProductSetUrlEntity($idProductSet, $idLocale);
}

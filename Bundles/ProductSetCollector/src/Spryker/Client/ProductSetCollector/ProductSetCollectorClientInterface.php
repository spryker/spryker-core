<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetCollector;

/**
 * @method \Spryker\Client\ProductSetCollector\ProductSetCollectorFactory getFactory()
 */
interface ProductSetCollectorClientInterface
{

    /**
     * Specification:
     * - Returns
     *
     * @api
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getProductSetList($limit = null, $offset = null);

}

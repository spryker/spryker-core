<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

interface ProductLabelStorageFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function publishLabelDictionary();

    /**
     * @api
     *
     * @return void
     */
    public function unpublishLabelDictionary();

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishProductLabel(array $productAbstractIds);

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds);
}

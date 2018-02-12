<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Business;

interface ProductSearchConfigStorageFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function publish();

    /**
     * @api
     *
     * @return void
     */
    public function unpublish();
}

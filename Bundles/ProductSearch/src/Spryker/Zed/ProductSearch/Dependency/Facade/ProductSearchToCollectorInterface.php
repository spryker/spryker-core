<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Dependency\Facade;

interface ProductSearchToCollectorInterface
{

    /**
     * @return string
     */
    public function getSearchIndexName();

    /**
     * @return string
     */
    public function getSearchDocumentType();

}

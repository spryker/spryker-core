<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business;

interface CategoryPageSearchFacadeInterface
{
    /**
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds);

    /**
     * @api
     *
     * @param array $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds);
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business;

interface CmsBlockCategoryStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds);

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function refreshOrUnpublish(array $categoryIds);
}

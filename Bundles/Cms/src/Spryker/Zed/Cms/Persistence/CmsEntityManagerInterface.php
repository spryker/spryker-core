<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

interface CmsEntityManagerInterface
{
    /**
     * @param array $nonExistingEntityPaths
     *
     * @return void
     */
    public function deleteNonExistingCmsTemplateEntitiesByPaths(array $nonExistingEntityPaths): void;
}

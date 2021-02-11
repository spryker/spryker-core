<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Category;

interface CategoryClientInterface
{
    /**
     * Specification:
     * - Resolve template for category node
     * - Return only the template path or NULL
     *
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return string
     */
    public function getTemplatePathByNodeId($idCategoryNode, $localeName);
}

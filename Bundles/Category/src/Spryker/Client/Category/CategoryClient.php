<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Category;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Category\CategoryFactory getFactory()
 */
class CategoryClient extends AbstractClient implements CategoryClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return string
     */
    public function getTemplatePathByNodeId($idCategoryNode, $localeName)
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getTemplatePathByNodeId($idCategoryNode, $localeName);
    }
}

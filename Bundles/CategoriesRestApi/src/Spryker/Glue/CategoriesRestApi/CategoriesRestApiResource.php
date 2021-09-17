<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\Kernel\AbstractRestResource;

/**
 * @method \Spryker\Glue\CategoriesRestApi\CategoriesRestApiFactory getFactory()
 */
class CategoriesRestApiResource extends AbstractRestResource implements CategoriesRestApiResourceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $nodeId
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findCategoryNodeById(int $nodeId, string $localeName): ?RestResourceInterface
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->findCategoryNodeById($nodeId, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $nodeIds
     * @param string $localeName
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function findCategoryNodeByIds(array $nodeIds, string $localeName): array
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->findCategoryNodeByIds($nodeIds, $localeName);
    }
}

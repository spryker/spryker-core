<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence\Propel;

use Orm\Zed\Url\Persistence\Base\SpyUrlQuery as BaseSpyUrlQuery;
use Spryker\Zed\Url\Persistence\Exception\UnknownResourceTypeException;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_url' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyUrlQuery extends BaseSpyUrlQuery
{
    /**
     * Used to map a resource type to a column
     *
     * @see AbstractSpyUrl::RESOURCE_PREFIX
     *
     * @var string
     */
    public const RESOURCE_PREFIX = 'FkResource';

    /**
     * @param string $resourceType
     * @param array $resourceIds
     *
     * @throws \Spryker\Zed\Url\Persistence\Exception\UnknownResourceTypeException
     *
     * @return static
     */
    public function filterByResourceTypeAndIds($resourceType, array $resourceIds)
    {
        $bumps = explode('_', $resourceType);
        $bumps = array_map('ucfirst', $bumps);

        $functionName = 'FilterBy' . static::RESOURCE_PREFIX . implode('', $bumps) . '_In';
        if (!method_exists($this, $functionName)) {
            throw new UnknownResourceTypeException(
                sprintf(
                    'Tried to set a resource type that is unknown. ResourceType: %s',
                    $resourceType,
                ),
            );
        }

        return $this->$functionName($resourceIds);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence\Propel;

use Orm\Zed\Url\Persistence\Base\SpyUrl as BaseSpyUrl;
use Spryker\Zed\Url\Persistence\Exception\MissingResourceException;
use Spryker\Zed\Url\Persistence\Exception\UnknownResourceTypeException;

/**
 * Skeleton subclass for representing a row from the 'spy_url' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyUrl extends BaseSpyUrl
{
    public const RESOURCE_DATA_NAME = 'name';
    public const RESOURCE_DATA_VALUE = 'value';
    /**
     * Used to map a row to its resource type
     * @see AbstractSpyUrlQuery::RESOURCE_PREFIX
     */
    public const RESOURCE_PREFIX = 'fk_resource_';

    /**
     * @return string
     */
    public function getResourceType()
    {
        $resourceData = $this->findResourceData();

        $resourceType = str_replace(static::RESOURCE_PREFIX, '', $resourceData[static::RESOURCE_DATA_NAME]);

        return $resourceType;
    }

    /**
     * @return int
     */
    public function getResourceId()
    {
        $resourceData = $this->findResourceData();

        return $resourceData[static::RESOURCE_DATA_VALUE];
    }

    /**
     * @throws \Spryker\Zed\Url\Persistence\Exception\MissingResourceException
     *
     * @return array
     */
    protected function findResourceData()
    {
        foreach (get_object_vars($this) as $name => $value) {
            if (strpos($name, static::RESOURCE_PREFIX) !== 0) {
                continue;
            }
            if ($value !== null) {
                return [
                    static::RESOURCE_DATA_NAME => $name,
                    static::RESOURCE_DATA_VALUE => $value,
                ];
            }
        }

        throw new MissingResourceException(
            sprintf(
                'Encountered a URL entity that is missing a resource: %s',
                json_encode($this->toArray())
            )
        );
    }

    /**
     * @param string $resourceType
     * @param int $resourceId
     *
     * @return $this
     */
    public function setResource($resourceType, $resourceId)
    {
        $setterName = $this->getSetterName($resourceType);
        $this->$setterName($resourceId);

        return $this;
    }

    /**
     * @param string $resourceType
     *
     * @throws \Spryker\Zed\Url\Persistence\Exception\UnknownResourceTypeException
     *
     * @return string
     */
    protected function getSetterName($resourceType)
    {
        $bumps = explode('_', $resourceType);
        $bumps = array_map('ucfirst', $bumps);

        $setterName = 'setFkResource' . implode('', $bumps);
        if (!method_exists($this, $setterName)) {
            throw new UnknownResourceTypeException(
                sprintf(
                    'Tried to set a resource type that is unknown. ResourceType: %s',
                    $resourceType
                )
            );
        }

        return $setterName;
    }
}

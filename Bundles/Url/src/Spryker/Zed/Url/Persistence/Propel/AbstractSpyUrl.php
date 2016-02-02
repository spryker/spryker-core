<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Persistence\Propel;

use Spryker\Zed\Url\Persistence\Exception\MissingResourceException;
use Spryker\Zed\Url\Persistence\Exception\UnknownResourceTypeException;
use Orm\Zed\Url\Persistence\Base\SpyUrl as BaseSpyUrl;

/**
 * Skeleton subclass for representing a row from the 'spy_url' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyUrl extends BaseSpyUrl
{

    /**
     * @throws \Spryker\Zed\Url\Persistence\Exception\MissingResourceException
     *
     * @return string
     */
    public function getResourceType()
    {
        $resourceData = $this->findResourceData();

        $resourceType = str_replace('fk_resource_', '', $resourceData['name']);

        return $resourceType;
    }

    /**
     * @throws \Spryker\Zed\Url\Persistence\Exception\MissingResourceException
     *
     * @return int
     */
    public function getResourceId()
    {
        $resourceData = $this->findResourceData();

        return $resourceData['value'];
    }

    /**
     * @throws \Spryker\Zed\Url\Persistence\Exception\MissingResourceException
     *
     * @return array
     */
    protected function findResourceData()
    {
        foreach (get_object_vars($this) as $name => $value) {
            if (strpos($name, 'fk_resource_') !== 0) {
                continue;
            }
            if ($value !== null) {
                return [
                    'name' => $name,
                    'value' => $value,
                ];
            }
        }

        throw new MissingResourceException(
            sprintf(
                'Encountered a URL entity that is missing a resource. Url ID: %s',
                $this->getIdUrl()
            )
        );
    }

    /**
     * @param string $resourceType
     * @param int $resourceId
     *
     * @throws \Spryker\Zed\Url\Persistence\Exception\UnknownResourceTypeException
     *
     * @return self
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

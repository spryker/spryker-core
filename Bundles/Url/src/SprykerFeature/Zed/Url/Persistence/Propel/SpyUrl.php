<?php

namespace SprykerFeature\Zed\Url\Persistence\Propel;

use SprykerFeature\Zed\Url\Persistence\Exception\MissingResourceException;
use SprykerFeature\Zed\Url\Persistence\Exception\UnknownResourceTypeException;
use SprykerFeature\Zed\Url\Persistence\Propel\Base\SpyUrl as BaseSpyUrl;

/**
 * Skeleton subclass for representing a row from the 'spy_url' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SpyUrl extends BaseSpyUrl
{
    /**
     * @return string
     * @throws MissingResourceException
     */
    public function getResourceType()
    {
        $resourceData = $this->findResourceData();

        $fkName = str_replace('fk_resource_', '', $resourceData['name']);
        $resourceType = str_replace('_id', '', $fkName);

        return $resourceType;
    }

    /**
     * @return int
     * @throws MissingResourceException
     */
    public function getResourceId()
    {
        $resourceData = $this->findResourceData();

        return $resourceData['value'];
    }

    /**
     * @return array
     * @throws MissingResourceException
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
                    'value' => $value
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
     * @return $this
     * @throws UnknownResourceTypeException
     */
    public function setResource($resourceType, $resourceId)
    {
        $setterName = $this->getSetterName($resourceType);
        $this->$setterName($resourceId);

        return $this;
    }

    /**
     * @param $resourceType
     *
     * @return string
     * @throws UnknownResourceTypeException
     */
    protected function getSetterName($resourceType)
    {
        $setterName = 'setFkResource' . ucfirst(strtolower($resourceType));
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

<?php

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

use SprykerFeature\Shared\UrlExporter\Code\KeyBuilder\ResourceKeyBuilder as SharedKeyBuilder;

class ResourceKeyBuilder extends SharedKeyBuilder
{
    /**
     * @var string
     */
    protected $resourceType;

    /**
     * @param string $resourceType
     *
     * @return $this
     */
    public function setResourceType($resourceType)
    {
        $this->resourceType = $resourceType;

        return $this;
    }

    /**
     * @return string
     */
    protected function getResourceType()
    {
        return $this->resourceType;
    }
}

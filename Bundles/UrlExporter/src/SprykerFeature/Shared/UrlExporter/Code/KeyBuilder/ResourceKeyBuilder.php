<?php

namespace SprykerFeature\Shared\UrlExporter\Code\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderTrait;

abstract class ResourceKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param array $identifier
     *
     * @return string
     */
    protected function buildKey($identifier)
    {
        /**
         * TODO: remove this quick hack
         *
         * called from CategoryTreeBuilder->createTreeFromCategoryNode()
         * in ./vendor/spryker/spryker/Bundles/CategoryExporter/src/SprykerFeature/Sdk/CategoryExporter/Builder/CategoryTreeBuilder.php
         */
        if (is_array($identifier) && isset($identifier['resourceType'])) {
            $identifier = $identifier['resourceType'];
        }

        return $this->getResourceType() . '.' . $identifier;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'resource';
    }

    /**
     * @return string
     */
    abstract protected function getResourceType();
}

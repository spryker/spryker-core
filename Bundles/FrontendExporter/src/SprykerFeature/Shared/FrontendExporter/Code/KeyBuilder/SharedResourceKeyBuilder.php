<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder;

abstract class SharedResourceKeyBuilder implements KeyBuilderInterface
{

    use KeyBuilderTrait;

    /**
     * @param array $identifier
     *
     * @return string
     */
    protected function buildKey($identifier)
    {
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

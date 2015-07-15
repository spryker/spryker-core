<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\CategoryExporter\Code\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderTrait;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

abstract class NavigationKeyBuilder implements KeyBuilderInterface
{

    use KeyBuilderTrait;

    /**
     * @param mixed $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return 'navigation';
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'category';
    }

}

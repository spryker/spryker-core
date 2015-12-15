<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\CategoryExporter\Code\KeyBuilder;

use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderTrait;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

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

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion\Fixtures;

class BundleNameFinder extends \Spryker\Zed\Kernel\BundleNameFinder
{

    public function getBundleNames()
    {
        return [
            'BundleA',
            'BundleB',
            'BundleC',
            'BundleD',
        ];
    }

}

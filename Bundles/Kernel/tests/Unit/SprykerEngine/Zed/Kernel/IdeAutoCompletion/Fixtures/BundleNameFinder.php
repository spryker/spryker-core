<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion\Fixtures;

class BundleNameFinder extends \SprykerEngine\Zed\Kernel\BundleNameFinder
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

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class InvalidForeignBundleFilter implements DependencyFilterInterface
{

    /**
     * @var array
     */
    private $invalidBundlesNames = [
        'AbstractSniffs',
        'X',
        'TwoFive',
        'TwoFour',
        'TwoThree',
        'TwoTwo',
        'Two',
        ''
    ];

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        if (!isset($dependency[DependencyTree::META_FOREIGN_BUNDLE])) {
            echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($dependency) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
        }
        return in_array($dependency[DependencyTree::META_FOREIGN_BUNDLE], $this->invalidBundlesNames);
    }

}

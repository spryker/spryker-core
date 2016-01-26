<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReaderFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;

/**
 * This filter is used to show only the dependencies without out any pre-ordering by bundle or foreign bundles
 *
 * `$array = [
 *      'Application' => [
 *          'Bundle' => [
 *              'ForeignBundle' => [
 *                  [
 *                      'dependency 1'
 *                  ],
 *                  [
 *                      'dependency 2'
 *                  ],
 *              ]
 *          ]
 *      ]
 * ]`
 *
 * will return
 *
 * `$array = [
 *      [
 *          'dependency 1'
 *      ],
 *      [
 *          'dependency 2'
 *      ],
 * ]`
 */
class DependencyTreeReaderFilter implements DependencyTreeReaderInterface
{

    /**
     * @var DependencyTreeReaderInterface
     */
    private $dependencyTreeReader;

    /**
     * @param DependencyTreeReaderInterface $dependencyTreeReader
     */
    public function __construct(DependencyTreeReaderInterface $dependencyTreeReader)
    {
        $this->dependencyTreeReader = $dependencyTreeReader;
    }

    /**
     * @return array
     */
    public function read()
    {
        $filteredDependencies = [];

        foreach ($this->dependencyTreeReader->read() as $foreignBundles) {
            foreach ($foreignBundles as $dependencies) {
                foreach ($dependencies as $dependency) {
                    $filteredDependencies[] = $dependency;
                }
            }
        }

        return $filteredDependencies;
    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

use Spryker\Zed\Library\Service\GraphViz;

class SimpleGraphBuilder implements GraphBuilderInterface
{

    /**
     * @var GraphViz
     */
    private $graph;

    /**
     * @var array
     */
    protected $clusterAttributes = [
        'fontname' => 'Verdana',
        'fillcolor' => '#cfcfcf',
        'style' => 'filled',
        'color' => '#ffffff',
        'fontsize' => 12,
        'fontcolor' => 'black',
    ];

    /**
     * @var array
     */
    protected $layerAttributes = [
        'fillcolor' => '#cfcfcf',
        'style' => 'filled',
        'color' => '#999999',
        'fontsize' => 12,
    ];

    /**
     * @var array
     */
    protected $rootFileAttributes = [
        'fillcolor' => '#cfcfcf',
        'shape' => 'diamond',
        'fontsize' => 8,
    ];

    public function __construct()
    {
        $this->graph = new GraphViz(true, [], 'Bundle Dependencies', false, true);
    }

    /**
     * @param array $dependencyTree
     *
     * @return bool
     */
    public function build(array $dependencyTree)
    {
        foreach ($dependencyTree as $bundle => $foreignBundles) {
            $this->graph->addNode($bundle);
        }

        foreach ($dependencyTree as $bundle => $foreignBundles) {
            foreach ($foreignBundles as $foreignBundle => $meta) {
                $this->graph->addEdge([$bundle => $foreignBundle]);
            }
        }

        return $this->graph->image('svg', 'dot');
    }

}

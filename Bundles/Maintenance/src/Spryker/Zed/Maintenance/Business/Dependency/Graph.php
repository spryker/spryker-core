<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Dependency;

use Spryker\Zed\Library\GraphViz\Adapter\PhpDocumentorGraphAdapter;
use Spryker\Zed\Library\GraphViz\GraphVizInterface;

class Graph
{

    /**
     * @var BundleParser
     */
    protected $bundleParser;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var array
     */
    protected $graphDefault = ['fontname' => 'Verdana', 'labelfontname' => 'Verdana', 'nodesep' => 0.6, 'ranksep' => 0.8];

    /**
     * @var string
     */
    protected $format = 'svg';

    /**
     * @var GraphVizInterface
     */
    protected $graph;

    /**
     * @param BundleParser $bundleParser
     * @param Manager $manager
     * @param GraphVizInterface $graph
     */
    public function __construct(BundleParser $bundleParser, Manager $manager, GraphVizInterface $graph)
    {
        $this->bundleParser = $bundleParser;
        $this->manager = $manager;
        $this->graph = $graph;
    }

    /**
     * @param string $bundleName
     *
     * @return bool
     */
    public function draw($bundleName)
    {
        $outgoingDependencies = $this->bundleParser->parseOutgoingDependencies($bundleName);
        $incomingDependencies = $this->manager->parseIncomingDependencies($bundleName);

        $this->graph->addNode($bundleName);

        foreach (array_keys($outgoingDependencies) as $foreignBundleName) {
            $isEngine = $this->bundleParser->isEngine($foreignBundleName);

            $attributes = [];

            if ($isEngine) {
                $attributes['style'] = 'filled';
                $attributes['fillcolor'] = '#e9e9e9';
            }

            $this->graph->addNode($foreignBundleName, $attributes);
        }

        foreach (array_keys($incomingDependencies) as $foreignBundleName) {
            $this->graph->addNode($foreignBundleName);
        }

        $attributes = ['fontsize' => 10];
        foreach ($outgoingDependencies as $foreignBundleName => $count) {
            $attributes['label'] = $count;
            $this->graph->addEdge($bundleName, $foreignBundleName, $attributes);
        }

        foreach ($incomingDependencies as $foreignBundleName => $count) {
            $attributes['label'] = $count;
            $this->graph->addEdge($foreignBundleName, $bundleName, $attributes);
        }

        return $this->graph->render($this->format);
    }

}

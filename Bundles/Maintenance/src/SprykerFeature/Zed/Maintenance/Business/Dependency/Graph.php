<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Dependency;

use SprykerFeature\Zed\Library\Service\GraphViz;

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

    protected $graphDefault = ['fontname' => 'Verdana', 'labelfontname' => 'Verdana', 'nodesep' => 0.6, 'ranksep' => 0.8];

    protected $format = 'svg';

    public function __construct(BundleParser $bundleParser, Manager $manager)
    {
        $this->bundleParser = $bundleParser;
        $this->manager = $manager;
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

        $graph = new GraphViz(true, $this->graphDefault, 'G', false, true);
        $graph->addNode($bundleName);

        foreach (array_keys($outgoingDependencies) as $foreignBundleName) {
            $isEngine = $this->bundleParser->isEngine($foreignBundleName);

            $attributes = [];

            if ($isEngine) {
                $attributes['style'] = 'filled';
                $attributes['fillcolor'] = '#e9e9e9';
            }

            $graph->addNode($foreignBundleName, $attributes);
        }

        foreach (array_keys($incomingDependencies) as $foreignBundleName) {
            $graph->addNode($foreignBundleName);
        }

        $attributes = ['fontsize' => 10];
        foreach ($outgoingDependencies as $foreignBundleName => $count) {
            $attributes['label'] = $count;
            $graph->addEdge([$bundleName => $foreignBundleName], $attributes);
        }

        foreach ($incomingDependencies as $foreignBundleName => $count) {
            $attributes['label'] = $count;
            $graph->addEdge([$foreignBundleName => $bundleName], $attributes);
        }

        return $graph->image($this->format, 'dot');
    }

}

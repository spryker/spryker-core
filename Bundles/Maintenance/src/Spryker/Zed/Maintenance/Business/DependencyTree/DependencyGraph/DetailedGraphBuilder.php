<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorClient;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorFacade;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorQueryContainer;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\UseStatement;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class DetailedGraphBuilder implements GraphBuilderInterface
{

    /**
     * @var \Spryker\Shared\Graph\GraphInterface
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

    /**
     * @param \Spryker\Shared\Graph\GraphInterface $graph
     */
    public function __construct(GraphInterface $graph)
    {
        $this->graph = $graph;
    }

    /**
     * @param array $dependencyTree
     *
     * @return bool
     */
    public function build(array $dependencyTree)
    {
        $this->buildGraph($dependencyTree);

        return $this->graph->render('svg');
    }

    /**
     * @param array $dependencyTree
     *
     * @return void
     */
    private function buildGraph(array $dependencyTree)
    {
        foreach ($dependencyTree as $dependency) {
            $bundle = $dependency[DependencyTree::META_BUNDLE];
            $foreignBundle = $dependency[DependencyTree::META_FOREIGN_BUNDLE];

            $group = $this->getGroup($bundle, $foreignBundle);
            $rootNodeId = $this->getRootNodeId($bundle);
            $this->graph->addNode($rootNodeId, [], $group);

            $this->addRootBundleLayer($dependency, $group);
            $this->addRootFile($dependency, $group);
            $this->addForeignBundleLayer($dependency, $group);
        }
    }

    /**
     * @param string $bundle
     * @param string $foreignBundle
     *
     * @return string
     */
    private function getGroup($bundle, $foreignBundle)
    {
        return implode(':', [$bundle, $foreignBundle]);
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    private function getRootNodeId($bundle)
    {
        return $bundle;
    }

    /**
     * @param array $dependency
     * @param string $group
     *
     * @return void
     */
    private function addRootBundleLayer(array $dependency, $group)
    {
        $rootBundleLayerNodeId = $this->getRootBundleLayerNodeId($dependency);
        $label = $dependency[DependencyTree::META_LAYER];
        $this->layerAttributes['label'] = $label;
        $this->graph->addNode($rootBundleLayerNodeId, $this->layerAttributes, $group);
        $this->graph->addEdge($this->getRootNodeId($dependency[DependencyTree::META_BUNDLE]), $rootBundleLayerNodeId);
    }

    /**
     * @param array $dependencyInformation
     *
     * @return string
     */
    private function getRootBundleLayerNodeId(array $dependencyInformation)
    {
        return $dependencyInformation[DependencyTree::META_LAYER];
    }

    /**
     * @param array $dependencyInformation
     * @param string $group
     *
     * @return void
     */
    private function addRootFile(array $dependencyInformation, $group)
    {
        $rootFileNodeId = $this->getRootFileNodeId($dependencyInformation);
        $this->graph->addNode($rootFileNodeId, $this->rootFileAttributes, $group);
        $this->graph->addEdge($this->getRootBundleLayerNodeId($dependencyInformation), $rootFileNodeId);
    }

    /**
     * @param array $dependencyInformation
     *
     * @return string
     */
    private function getRootFileNodeId(array $dependencyInformation)
    {
        return substr($dependencyInformation[DependencyTree::META_FILE], 0, -4);
    }

    /**
     * @param array $dependencyInformation
     * @param string $group
     *
     * @return void
     */
    private function addForeignBundleLayer(array $dependencyInformation, $group)
    {
        $foreignBundleLayerNodeId = $this->getForeignBundleLayerNodeId($dependencyInformation);
        $label = $dependencyInformation[DependencyTree::META_FOREIGN_BUNDLE]  . ' ' . $dependencyInformation[DependencyTree::META_FOREIGN_LAYER];
        $attributes = $this->layerAttributes;
        $attributes['label'] = $label;
        $this->graph->addNode($foreignBundleLayerNodeId, $attributes, $group);

        $this->graph->addEdge(
            $this->getRootFileNodeId($dependencyInformation),
            $foreignBundleLayerNodeId,
            [
                'label' => $this->getForeignUsage($dependencyInformation[DependencyTree::META_FINDER]) . ' : ' . $dependencyInformation[DependencyTree::META_FOREIGN_CLASS_NAME],
                'fontsize' => 8,
            ]
        );
    }

    /**
     * @param array $dependencyInformation
     *
     * @return string
     */
    private function getForeignBundleLayerNodeId(array $dependencyInformation)
    {
        return implode(
            ':',
            [
                $dependencyInformation[DependencyTree::META_FOREIGN_BUNDLE],
                $dependencyInformation[DependencyTree::META_FOREIGN_LAYER],
            ]
        );
    }

    /**
     * @param string $finderName
     *
     * @return string
     */
    private function getForeignUsage($finderName)
    {
        $mapped = [
            LocatorClient::class => 'Client',
            LocatorFacade::class => 'Facade',
            LocatorQueryContainer::class => 'QueryContainer',
            UseStatement::class => 'Use',
        ];

        return $mapped[$finderName];
    }

}

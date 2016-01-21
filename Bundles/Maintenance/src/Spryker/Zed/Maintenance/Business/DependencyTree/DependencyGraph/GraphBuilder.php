<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\AbstractDependencyFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorClient;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorFacade;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\LocatorQueryContainer;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFinder\UseStatement;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Library\Service\GraphViz;

class GraphBuilder
{

    /**
     * @var GraphViz
     */
    private $graph;

    /**
     * @var GraphViz
     */
    private $currentGraph;

    /**
     * @var AbstractDependencyFilter[]
     */
    private $filter;

    /**
     * @var array
     */
    protected $graphAttributes = ['fontname' => 'Verdana', 'labelfontname' => 'Verdana', 'nodesep' => 0.6, 'ranksep' => 0.8];

    /**
     * @var array
     */
    protected $clusterAttributes = ['fontname' => 'Verdana', 'fillcolor' => '#cfcfcf', 'style' => 'filled', 'color' => '#ffffff', 'fontsize' => 12, 'fontcolor' => 'black'];

    /**
     * @param array $filter
     */
    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
        $this->graph = new GraphViz(true, $this->graphAttributes, 'Bundle Dependencies', false, true);
    }

    /**
     * @param array $dependencyTree
     *
     * @return bool
     */
    public function build(array $dependencyTree)
    {
        $dependencyTree = $this->filterDependencyTree($dependencyTree);
        $this->buildGraph($dependencyTree);

        return $this->graph->image('svg', 'dot');
    }

    /**
     * @param array $dependencyTree
     *
     * @return void
     */
    private function buildGraph(array $dependencyTree)
    {
        foreach ($dependencyTree as $application => $bundles) {
            $this->buildBundlesGraph($application, $bundles);
        }
    }

    /**
     * @param string $application
     * @param array $bundles
     *
     * @return void
     */
    private function buildBundlesGraph($application, array $bundles)
    {
        foreach ($bundles as $bundle => $dependentBundles) {
//            $this->currentGraph = $this->getSubGraph($bundle);
            $this->buildBundleGraph($bundle, $dependentBundles);
//            $this->graph->addSubgraph($bundle, $bundle, [], $application);
        }
    }

    /**
     * @param string $bundle
     *
     * @return GraphViz
     */
    private function getSubGraph($bundle)
    {
        return new GraphViz(true, [], $bundle . ' Dependencies', false, true);
    }

    /**
     * @param string $bundle
     * @param array $dependentBundles
     *
     * @return void
     */
    private function buildBundleGraph($bundle, array $dependentBundles)
    {
        foreach ($dependentBundles as $dependentBundle => $dependencyInformationCollection) {
            $this->buildBundleDependencyGraph($bundle, $dependentBundle, $dependencyInformationCollection);
        }
    }

    /**
     * @param string $bundle
     * @param string $dependentBundle
     * @param array $dependencyInformationCollection
     *
     * @return void
     */
    private function buildBundleDependencyGraph($bundle, $dependentBundle, $dependencyInformationCollection)
    {
        foreach ($dependencyInformationCollection as $dependencyInformation) {
            $group = $this->getGroupName($bundle, $dependentBundle, $dependencyInformation);

            $foundIn = $this->getFoundIn($dependencyInformation);
            $nodeFromId = $this->getNodeFromId($bundle, $dependentBundle, $dependencyInformation, $foundIn);
            $nodeFromLabel = $this->getNodeLabel($bundle, $foundIn);
            $this->graph->addNode($nodeFromId, ['label' => $nodeFromLabel], $group);

            $foundBy = $this->getFoundBy($dependencyInformation);
            $nodeToId = $this->getNodeToId($dependentBundle, $dependencyInformation, $foundBy, $foundIn);
            $nodeToLabel = $this->getNodeLabel($dependentBundle, $foundBy);
            $this->graph->addNode($nodeToId, ['label' => $nodeToLabel], $group);

            $this->graph->addEdge([$nodeFromId => $nodeToId]);

            $groupLabel = $this->getGroupLabel($bundle, $dependentBundle, $dependencyInformation);
            $this->graph->addCluster($group, $groupLabel, $this->clusterAttributes);
        }
    }

    /**
     * @param string $bundle
     * @param string $dependentBundle
     * @param array $dependencyInformation
     *
     * @return string
     */
    private function getGroupName($bundle, $dependentBundle, array $dependencyInformation)
    {
        $groupNameElements = [
            $bundle,
            $dependencyInformation[DependencyTree::META_LAYER],
            $dependentBundle,
            $dependencyInformation[DependencyTree::META_FOREIGN_LAYER],
        ];

        return implode(':', $groupNameElements);
    }

    /**
     * @param array $dependencyInformation
     *
     * @return string
     */
    private function getFoundIn(array $dependencyInformation)
    {
        return substr($dependencyInformation[DependencyTree::META_FILE], 0, -4);
    }

    /**
     * @param string $bundle
     * @param string $dependentBundle
     * @param array $dependencyInformation
     * @param string $foundIn
     *
     * @return string
     */
    private function getNodeFromId($bundle, $dependentBundle, array $dependencyInformation, $foundIn)
    {
        $idElements = [
            $bundle,
            $dependencyInformation[DependencyTree::META_LAYER],
            $foundIn,
            $dependentBundle,
            $dependencyInformation[DependencyTree::META_FOREIGN_LAYER],
        ];

        return implode(':', $idElements);
    }

    /**
     * @param string $title
     * @param string $subTitle
     *
     * @return string
     */
    private function getNodeLabel($title, $subTitle)
    {
        $nodeLabel = [$title, '<br/><font color="blue" point-size="10">' . $subTitle . '</font>'];

        return implode($nodeLabel);
    }

    /**
     * @param array $dependencyInformation
     *
     * @return string
     */
    private function getFoundBy(array $dependencyInformation)
    {
        return $this->getDependentInfo($dependencyInformation[DependencyTree::META_FINDER]);
    }

    /**
     * @param string $dependentBundle
     * @param array $dependencyInformation
     * @param string $foundBy
     * @param string $foundIn
     *
     * @return string
     */
    private function getNodeToId($dependentBundle, $dependencyInformation, $foundBy, $foundIn)
    {
        $idElements = [
            $dependentBundle,
            $dependencyInformation[DependencyTree::META_FOREIGN_LAYER],
            $foundBy,
            $foundIn,
        ];

        return implode(':', $idElements);
    }

    /**
     * @param array $dependencyTree
     *
     * @return array
     */
    private function filterDependencyTree(array $dependencyTree)
    {
        $filteredTree = [];

        foreach ($dependencyTree as $application => $bundles) {
            $filteredTree[$application] = [];
            foreach ($bundles as $bundle => $dependentBundles) {
                $filteredTree[$application][$bundle] = [];
                $context = ['bundle' => $bundle];
                foreach ($dependentBundles as $dependentBundle => $meta) {
                    $addBundle = true;
                    foreach ($this->filter as $filter) {
                        if ($filter->filter($dependentBundle, $context)) {
                            $addBundle = false;
                        }
                    }
                    if ($addBundle) {
                        $filteredTree[$application][$bundle][$dependentBundle] = $this->filterMeta($meta);
                    }
                }
            }
        }

        return $filteredTree;
    }

    /**
     * @param array $meta
     *
     * @return array
     */
    private function filterMeta(array $meta)
    {
        $filteredMeta = [];
        foreach ($meta as $item) {
            $itemHash = implode($item);
            $filteredMeta[$itemHash] = $item;
        }

        return array_values($filteredMeta);
    }

    /**
     * @param string $finderName
     *
     * @return string
     */
    private function getDependentInfo($finderName)
    {
        $mapped = [
            LocatorClient::class => 'Client',
            LocatorFacade::class => 'Facade',
            LocatorQueryContainer::class => 'QueryContainer',
            UseStatement::class => 'Use',
        ];

        return $mapped[$finderName];
    }

    /**
     * @param string $bundle
     * @param string $dependentBundle
     * @param array $dependencyInfo
     *
     * @return string
     */
    private function getGroupLabel($bundle, $dependentBundle, array $dependencyInfo)
    {
        $groupLabelElements = [
            $bundle,
            ($dependencyInfo[DependencyTree::META_LAYER] === 'noLayer') ? 'Default' : $dependencyInfo[DependencyTree::META_LAYER],
            '=>',
            $dependentBundle,
            ($dependencyInfo[DependencyTree::META_FOREIGN_LAYER] === 'noLayer') ? 'Default' : $dependencyInfo[DependencyTree::META_FOREIGN_LAYER]
        ];

        return implode(' ', $groupLabelElements);
    }

}

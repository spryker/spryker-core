<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Library\Service\GraphViz;
use Spryker\Zed\Maintenance\Business\MaintenanceFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method MaintenanceFacade getFacade()
 */
class DependencyController extends AbstractController
{

    const QUERY_BUNDLE = 'bundle';

    /**
     * @return array
     */
    public function indexAction()
    {
        $bundles = $this->getFacade()->getAllBundles();

        return $this->viewResponse([
            'bundles' => $bundles,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function outgoingAction(Request $request)
    {
        $bundleName = $request->query->get(self::QUERY_BUNDLE);

        $dependencies = $this->getFacade()->showOutgoingDependenciesForBundle($bundleName);

        return $this->viewResponse([
            self::QUERY_BUNDLE => $bundleName,
            'dependencies' => $dependencies,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function incomingAction(Request $request)
    {
        $bundleName = $request->query->get(self::QUERY_BUNDLE);

        $dependencies = $this->getFacade()->showIncomingDependenciesForBundle($bundleName);

        return $this->viewResponse([
            self::QUERY_BUNDLE => $bundleName,
            'dependencies' => $dependencies,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return StreamedResponse
     */
    public function graphAction(Request $request)
    {
        $bundleName = $request->query->get(self::QUERY_BUNDLE);
        $response = $this->getFacade()->drawDependencyGraph($bundleName);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @return StreamedResponse
     */
    public function dependencyTreeGraphAction()
    {
        $callback = function() {
            $this->getFacade()->drawDependencyTreeGraph();
        };

        return $this->streamedResponse($callback);
    }

    public function fooAction()
    {
        $data = '{
    "Application": "engine",
    "Auth": "engine",
    "Config": "engine",
    "Console": "engine",
    "Gui": "engine",
    "Installer": "engine",
    "Kernel": "engine",
    "Library": "engine",
    "Locale": "engine",
    "Propel": "engine",
    "Touch": "engine",
    "Transfer": "engine",
    "Twig": "engine"
}';
        $engineBundles = json_decode($data, true);
        $dependencyTree = $this->getFacade()->getDependencyTree();
        $graph = new GraphViz();
echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($dependencyTree['Zed']['CustomerCheckoutConnector']) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
        foreach ($dependencyTree['Zed'] as $bundle => $foreignBundles) {

            if (array_key_exists($bundle, $engineBundles)) {
                continue;
            }
            $graph->addNode($bundle);

        }
        foreach ($dependencyTree['Zed'] as $bundle => $foreignBundles) {

            foreach ($foreignBundles as $foreignBundle => $meta) {
                if (array_key_exists($foreignBundle, $engineBundles)) {
                    continue;
                }
                $graph->addEdge([$bundle => $foreignBundle]);
            }
        }

        echo $graph->image();die();
        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($dependencyTree) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();

    }

}

<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Controller;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use SprykerFeature\Zed\Maintenance\Communication\MaintenanceDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method MaintenanceFacade getFacade()
 * @method MaintenanceDependencyContainer getDependencyContainer()
 */
class StorageController extends AbstractController
{
    const REFERENCE_KEY = 'reference_key';

    /**
     * TODO This business logic must be behind the CollectorFacade
     *
     * @return array
     */
    public function indexAction()
    {
        $client = $this->getDependencyContainer()->createStorageClient();
        $totalCount = $client->getCountItems();

        $metaData = $this->getTimestamps($client);
        return $this->viewResponse(
            [
                'totalCount' => $totalCount,
                'metaData' => $metaData
            ]
        );
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $table = $this->getDependencyContainer()->createStorageTable();

        return $this->viewResponse(['table' => $table->render()]);
    }

    /**
     * @return JsonResponse
     */
    public function listAjaxAction()
    {
        $table = $this->getDependencyContainer()->createStorageTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }


    public function dropTimestampsAction()
    {
        $client = $this->getDependencyContainer()->createStorageClient();
        $metaData = $this->getTimestamps($client);
        Locator::getInstance()->collector()->facade()->deleteStorageTimestamps(array_keys($metaData)); // TODO Wrong use of facade
        return $this->redirectResponse('/maintenance/storage');
    }

    /**
     * @return RedirectResponse
     */
    public function deleteAllAction()
    {
        $numberOfDeletedEntried = $this->getDependencyContainer()->createStorageClient()->deleteAll();
        $this->addInfoMessage('Removed '.$numberOfDeletedEntried.' entries from storage.');
        return $this->redirectResponse('/maintenance/storage');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function keyAction(Request $request)
    {
        $key = $request->get('key');
        $value = $this->getDependencyContainer()->createStorageClient()->get($key);

        return $this->viewResponse([
            'value' => var_export($value, true),
            'key' => $key,
            'referenceKey' => $this->getReferenceKey($value),
        ]);
    }

    /**
     * @param $value
     *
     * @return string
     */
    protected function getReferenceKey($value)
    {
        $referenceKey = '';

        if (is_array($value) && isset($value[self::REFERENCE_KEY])) {
            $referenceKey = $value[self::REFERENCE_KEY];
        }

        return $referenceKey;
    }

    /**
     * TODO This should be hidden behind CollectorFacade
     * @param $client
     * @return array
     */
    protected function getTimestamps($client)
    {
        $metaData = [];

        $allKeys = $client->getAllKeys();
        foreach ($allKeys as $key) {
            $key = str_replace('kv:', '', $key);

            if (strpos($key, '.timestamp') !== false) {
                $metaData[$key] = $client->get($key);
            }
        }
        return $metaData;
    }

}

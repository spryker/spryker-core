<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use SprykerFeature\Zed\Maintenance\Communication\MaintenanceDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method MaintenanceFacade getFacade()
 * @method MaintenanceDependencyContainer getDependencyContainer()
 */
class StorageDataController extends AbstractController
{

    /**
     * @return array
     */
    public function storageAction()
    {
        $table = $this->getDependencyContainer()->createStorageTable();

        return $this->viewResponse(['table' => $table->render()]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createStorageTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function storageKeyAction(Request $request)
    {
        $key = $request->get('key');
        $value = $this->getDependencyContainer()->getStorageClient()->get($key);

        $link = '';

        if (is_array($value) && isset($value['reference_key'])) {
            $link = '<a href="/maintenance/index/storage-key?key=' . $value['reference_key'] . '">' . $value['reference_key'] . '</a>';
        }

        return $this->viewResponse([
            'value' => var_export($value, true),
            'key' => $key,
            'link' => $link,
        ]);
    }

}

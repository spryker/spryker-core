<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Storage\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Storage\Business\StorageFacade;
use SprykerFeature\Zed\Storage\Communication\StorageDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method StorageFacade getFacade()
 * @method StorageDependencyContainer getDependencyContainer()
 */
class MaintenanceController extends AbstractController
{

    const REFERENCE_KEY = 'reference_key';
    const MESSAGE_REMOVED_ENTRIES = 'Removed "%" entries from storage.';
    const URL_STORAGE_MAINTENANCE = '/storage/maintenance';

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse(
            [
                'totalCount' => $this->getFacade()->getTotalCount(),
                'metaData' => $this->getFacade()->getTotalCount(),
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

    /**
     * @return RedirectResponse
     */
    public function dropTimestampsAction()
    {
        $timestamps = $this->getFacade()->getTimestamps();
        $this->getDependencyContainer()->createCollectorFacade()->deleteStorageTimestamps(array_keys($timestamps));

        return $this->redirectResponse(self::URL_STORAGE_MAINTENANCE);
    }

    /**
     * @return RedirectResponse
     */
    public function deleteAllAction()
    {
        $numberOfDeletedEntries = $this->getFacade()->deleteAll();
        $this->addInfoMessage(
            sprintf(self::MESSAGE_REMOVED_ENTRIES, $numberOfDeletedEntries)
        );

        return $this->redirectResponse(self::URL_STORAGE_MAINTENANCE);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function keyAction(Request $request)
    {
        $key = $request->get('key');
        $value = $this->getFacade()->get($key);

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

}

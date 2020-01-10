<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Storage\Business\StorageFacadeInterface getFacade()
 * @method \Spryker\Zed\Storage\Communication\StorageCommunicationFactory getFactory()
 */
class MaintenanceController extends AbstractController
{
    public const REFERENCE_KEY = 'reference_key';
    public const URL_STORAGE_MAINTENANCE = '/storage/maintenance';

    /**
     * @return array
     */
    public function indexAction()
    {
        $count = $this->getFacade()->getTotalCount();

        return $this->viewResponse(
            [
                'totalCount' => $count,
            ]
        );
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $table = $this->getFactory()->createStorageTable();

        return $this->viewResponse(['table' => $table->render()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAjaxAction()
    {
        $table = $this->getFactory()->createStorageTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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
     * @param mixed $value
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

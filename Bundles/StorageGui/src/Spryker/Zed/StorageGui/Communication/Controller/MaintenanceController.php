<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\StorageGui\StorageGuiConfig;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\StorageGui\Communication\StorageGuiCommunicationFactory getFactory()
 */
class MaintenanceController extends AbstractController
{
    /**
     * @var string
     */
    public const URL_STORAGE_MAINTENANCE = '/storage-gui/maintenance';

    /**
     * @var string
     */
    protected const REFERENCE_KEY = 'reference_key';

    /**
     * @var string
     */
    protected const URL_PARAM_KEY = 'key';

    /**
     * @return array<string, int>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'totalCount' => $this->getFactory()->getStorageFacade()->getTotalCount(),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function listAction(): array
    {
        return $this->viewResponse([
            'table' => $this->getFactory()->createStorageTable()->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listAjaxAction(): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createStorageTable()->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function keyAction(Request $request): array
    {
        $key = preg_replace(sprintf('/^%s/', StorageGuiConfig::KV_PREFIX), '', $request->get('key'));
        $value = $this->getFactory()->getStorageFacade()->get($key);

        return $this->viewResponse([
            'value' => $value,
            'key' => $request->get('key'),
            'referenceKey' => $this->getReferenceKey($value),
        ]);
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function getReferenceKey($value): string
    {
        $referenceKey = '';

        if (is_array($value) && isset($value[static::REFERENCE_KEY])) {
            $referenceKey = $value[static::REFERENCE_KEY];
        }

        return $referenceKey;
    }
}

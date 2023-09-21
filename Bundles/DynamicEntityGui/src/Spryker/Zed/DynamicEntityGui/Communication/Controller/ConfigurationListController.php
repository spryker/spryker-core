<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\DynamicEntityGui\Communication\DynamicEntityGuiCommunicationFactory getFactory()
 */
class ConfigurationListController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        $dynamicDataConfigurationTable = $this->getFactory()->createDynamicDataConfigurationTable();
        $isSchemaFileActual = $this->getFactory()->createOpenApiSchemaChecker()->isSchemaFileActual();

        return $this->viewResponse([
            'dynamicDataConfigurationTable' => $dynamicDataConfigurationTable->render(),
            'isSchemaFileActual' => $isSchemaFileActual,
            'viewActionButtonOptions' => $isSchemaFileActual ? [] : ['class' => 'disabled'],
            'updateTimeInMinutes' => $this->getFactory()->getConfig()->getUpdateTime(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $dynamicDataConfigurationTable = $this->getFactory()->createDynamicDataConfigurationTable();

        return $this->jsonResponse(
            $dynamicDataConfigurationTable->fetchData(),
        );
    }
}

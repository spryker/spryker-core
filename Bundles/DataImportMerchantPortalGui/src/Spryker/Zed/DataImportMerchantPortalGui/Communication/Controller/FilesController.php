<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\DataImportMerchantPortalGui\Communication\DataImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class FilesController extends AbstractController
{
    /**
     * @var string
     */
    public const ID_DATA_IMPORT_MERCHANT_FILE_TABLE = 'data-import-merchant-file-table';

    /**
     * @uses \Spryker\Zed\DataImportMerchantPortalGui\Communication\Controller\ImportController::indexAction()
     *
     * @var string
     */
    protected const URL_IMPORT = '/data-import-merchant-portal-gui/import';

    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'idDataImportMerchantFileTable' => static::ID_DATA_IMPORT_MERCHANT_FILE_TABLE,
            'dataImportMerchantFileTableConfiguration' => $this->getFactory()
                ->createDataImportMerchantFileTableConfigurationProvider()
                ->getConfiguration(),
            'urlImport' => static::URL_IMPORT,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createDataImportMerchantFileGuiTableDataProvider(),
            $this->getFactory()->createDataImportMerchantFileTableConfigurationProvider()->getConfiguration(),
        );
    }
}

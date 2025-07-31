<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Communication\FileImportMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Business\FileImportMerchantPortalGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 */
class HistoryController extends AbstractController
{
    /**
     * @var string
     */
    protected const ID_MERCHANT_FILE_IMPORT_TABLE = 'table-file-import-history';

    /**
     * @var string
     */
    protected const URL_IMPORT_UPLOAD_FILE = '/file-import-merchant-portal-gui/import/upload-file';

    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'idMerchantFileImportTable' => static::ID_MERCHANT_FILE_IMPORT_TABLE,
            'merchantFileImportTableConfiguration' => $this->getFactory()
                ->createFileImportHistoryGuiTableConfigurationProvider()
                ->getConfiguration(),
            'urlImportUploadFile' => static::URL_IMPORT_UPLOAD_FILE,
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
            $this->getFactory()->createFileImportHistoryGuiTableDataProvider(),
            $this->getFactory()->createFileImportHistoryGuiTableConfigurationProvider()->getConfiguration(),
        );
    }
}

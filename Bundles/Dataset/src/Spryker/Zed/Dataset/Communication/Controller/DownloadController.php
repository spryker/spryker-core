<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacade getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetQueryContainer getQueryContainer()
 */

class DownloadController extends AbstractController
{
    const URL_PARAM_ID_DATASET = 'id-dataset';
    const CONTENT_DISPOSITION = 'Content-Disposition';
    const CONTENT_TYPE = 'Content-Type';
    const CONTENT_TYPE_CSV = 'text/plain';
    const FILE_EXTENTION = 'csv';
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $idDataset = $this->castId($request->query->get(static::URL_PARAM_ID_DATASET));
        $datasetTransfer = $this->getFacade()->getDatasetTransferById($idDataset);

        return $this->createResponse($datasetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $datasetTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(SpyDatasetEntityTransfer $datasetTransfer)
    {
        $content = $this->getFacade()->getDatasetContent($datasetTransfer);
        $response = new Response($content);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf('%s.%s', $datasetTransfer->getName(), static::FILE_EXTENTION)
        );
        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, static::CONTENT_TYPE_CSV);

        return $response;
    }
}

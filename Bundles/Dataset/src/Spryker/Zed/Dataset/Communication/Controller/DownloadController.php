<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Generated\Shared\Transfer\DatasetFilenameTransfer;
use Generated\Shared\Transfer\DatasetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacadeInterface getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 */
class DownloadController extends AbstractController
{
    public const URL_PARAM_ID_DATASET = 'id-dataset';
    public const CONTENT_DISPOSITION = 'Content-Disposition';
    public const CONTENT_TYPE = 'Content-Type';
    public const CONTENT_TYPE_CSV = 'text/plain';
    public const FILE_EXTENTION = 'csv';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $idDataset = $this->castId($request->query->get(static::URL_PARAM_ID_DATASET));
        $datasetTransfer = $this->getFacade()->getDatasetModelById((new DatasetTransfer())->setIdDataset($idDataset));

        return $this->createResponse($datasetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createResponse(DatasetTransfer $datasetTransfer): Response
    {
        $content = $this->getFacade()->getCsvByDataset($datasetTransfer);
        $response = new Response($content);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf('%s.%s', $this->getFacade()->getFilenameByDatasetName(
                (new DatasetFilenameTransfer())->setFilename($datasetTransfer->getName())
            )
                ->getFilename(), static::FILE_EXTENTION)
        );
        $response->headers->set(static::CONTENT_DISPOSITION, $disposition);
        $response->headers->set(static::CONTENT_TYPE, static::CONTENT_TYPE_CSV);

        return $response;
    }
}

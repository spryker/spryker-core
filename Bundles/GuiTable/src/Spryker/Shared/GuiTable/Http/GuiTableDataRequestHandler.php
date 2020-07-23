<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Http;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface;
use Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilderInterface;
use Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GuiTableDataRequestHandler implements GuiTableDataRequestHandlerInterface
{
    /**
     * @var \Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilderInterface
     */
    protected $dataRequestBuilder;

    /**
     * @var \Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatterInterface
     */
    protected $dataResponseFormatter;

    /**
     * @var \Spryker\Shared\GuiTable\Http\HttpResponseBuilderInterface
     */
    protected $httpResponseBuilder;

    /**
     * @param \Spryker\Shared\GuiTable\Http\DataRequest\DataRequestBuilderInterface $dataRequestBuilder
     * @param \Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatterInterface $dataResponseFormatter
     * @param \Spryker\Shared\GuiTable\Http\HttpResponseBuilderInterface $httpResponseBuilder
     */
    public function __construct(
        DataRequestBuilderInterface $dataRequestBuilder,
        DataResponseFormatterInterface $dataResponseFormatter,
        HttpResponseBuilderInterface $httpResponseBuilder
    ) {
        $this->dataRequestBuilder = $dataRequestBuilder;
        $this->dataResponseFormatter = $dataResponseFormatter;
        $this->httpResponseBuilder = $httpResponseBuilder;
    }

    /**
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\GuiTable\DataProvider\GuiTableDataProviderInterface $guiTableDataProvider
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleGetDataRequest(
        Request $request,
        GuiTableDataProviderInterface $guiTableDataProvider,
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        LocaleTransfer $localeTransfer
    ): Response {
        $guiTableDataRequestTransfer = $this->dataRequestBuilder->buildGuiTableDataRequestFromRequest(
            $request,
            $guiTableConfigurationTransfer,
            $localeTransfer
        );
        $guiTableDataResponseTransfer = $guiTableDataProvider->getData($guiTableDataRequestTransfer);

        return $this->httpResponseBuilder->buildResponse(
            $this->dataResponseFormatter->formatGuiTableDataResponse($guiTableDataResponseTransfer, $guiTableConfigurationTransfer)
        );
    }
}

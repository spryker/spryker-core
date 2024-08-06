<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Sender;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\TransferResponseCollectionTransfer;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToKernelAppFacadeInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Service\SalesPaymentMerchantToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class TransferRequestSender implements TransferRequestSenderInterface
{
 /**
  * @var \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToKernelAppFacadeInterface
  */
    protected SalesPaymentMerchantToKernelAppFacadeInterface $kernelAppFacade;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Dependency\Service\SalesPaymentMerchantToUtilEncodingServiceInterface
     */
    protected SalesPaymentMerchantToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToKernelAppFacadeInterface $kernelAppFacade
     * @param \Spryker\Zed\SalesPaymentMerchant\Dependency\Service\SalesPaymentMerchantToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        SalesPaymentMerchantToKernelAppFacadeInterface $kernelAppFacade,
        SalesPaymentMerchantToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->kernelAppFacade = $kernelAppFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<string, array<int, array<string, mixed>>|int> $transferRequestData
     * @param string $transferEndpoint
     *
     * @return \Generated\Shared\Transfer\TransferResponseCollectionTransfer
     */
    public function requestTransfer(
        array $transferRequestData,
        string $transferEndpoint
    ): TransferResponseCollectionTransfer {
        $acpHttpRequestTransfer = $this->createAcpHttpRequestTransfer($transferEndpoint, $transferRequestData);
        $acpHttpResponseTransfer = $this->kernelAppFacade->makeRequest($acpHttpRequestTransfer);

        $decodedResponse = (array)$this->utilEncodingService->decodeJson(
            $acpHttpResponseTransfer->getContentOrFail(),
            true,
        );

        $transferResponseCollectionTransfer = new TransferResponseCollectionTransfer();
        $transferResponseCollectionTransfer->fromArray($decodedResponse);

        return $transferResponseCollectionTransfer;
    }

    /**
     * @param string $transferEndpoint
     * @param array<string, array<int, array<string, mixed>>|int> $transferRequestData
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    public function createAcpHttpRequestTransfer(
        string $transferEndpoint,
        array $transferRequestData
    ): AcpHttpRequestTransfer {
        return (new AcpHttpRequestTransfer())
            ->setMethod(Request::METHOD_POST)
            ->setUri($transferEndpoint)
            ->setBody((string)$this->utilEncodingService->encodeJson($transferRequestData));
    }
}

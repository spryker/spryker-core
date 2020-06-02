<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ReturnReasonSearchTransfer;
use Generated\Shared\Transfer\ReturnReasonTransfer;
use Spryker\Zed\SalesReturnSearch\Business\DataMapper\ReturnReasonSearchDataMapperInterface;
use Spryker\Zed\SalesReturnSearch\Dependency\Service\SalesReturnSearchToUtilEncodingServiceInterface;

class ReturnReasonSearchMapper implements ReturnReasonSearchMapperInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnSearch\Dependency\Service\SalesReturnSearchToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\SalesReturnSearch\Business\DataMapper\ReturnReasonSearchDataMapperInterface
     */
    protected $returnReasonSearchDataMapper;

    /**
     * @param \Spryker\Zed\SalesReturnSearch\Dependency\Service\SalesReturnSearchToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\SalesReturnSearch\Business\DataMapper\ReturnReasonSearchDataMapperInterface $returnReasonSearchDataMapper
     */
    public function __construct(
        SalesReturnSearchToUtilEncodingServiceInterface $utilEncodingService,
        ReturnReasonSearchDataMapperInterface $returnReasonSearchDataMapper
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->returnReasonSearchDataMapper = $returnReasonSearchDataMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer $returnReasonTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonSearchTransfer $returnReasonSearchTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string[][] $returnReasonTranslations
     *
     * @return \Generated\Shared\Transfer\ReturnReasonSearchTransfer
     */
    public function mapReturnReasonTransferToReturnReasonSearchTransfer(
        ReturnReasonTransfer $returnReasonTransfer,
        ReturnReasonSearchTransfer $returnReasonSearchTransfer,
        LocaleTransfer $localeTransfer,
        array $returnReasonTranslations
    ): ReturnReasonSearchTransfer {
        $returnReasonSearchTransfer->fromArray($returnReasonTransfer->toArray(), true);
        $returnReasonSearchTransfer->setName(
            $returnReasonTranslations[$returnReasonTransfer->getGlossaryKeyReason()][$localeTransfer->getIdLocale()] ?? $returnReasonTransfer->getGlossaryKeyReason()
        );

        $returnReasonSearchData = $returnReasonSearchTransfer->toArray(true, true);

        $returnReasonSearchTransfer->setData(
            $this->returnReasonSearchDataMapper->mapReturnReasonDataToSearchData($returnReasonSearchData, $localeTransfer)
        );

        unset($returnReasonSearchData[ReturnReasonSearchTransfer::STRUCTURED_DATA]);

        $returnReasonSearchTransfer->setStructuredData(
            $this->utilEncodingService->encodeJson($returnReasonSearchData)
        );

        $returnReasonSearchTransfer->setLocale($localeTransfer->getLocaleName());

        return $returnReasonSearchTransfer;
    }
}

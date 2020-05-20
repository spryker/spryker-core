<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Business\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ReturnReasonPageSearchTransfer;
use Generated\Shared\Transfer\ReturnReasonTransfer;
use Spryker\Zed\SalesReturnPageSearch\Business\DataMapper\ReturnReasonPageSearchDataMapperInterface;
use Spryker\Zed\SalesReturnPageSearch\Dependency\Service\SalesReturnPageSearchToUtilEncodingServiceInterface;

class ReturnReasonPageSearchMapper implements ReturnReasonPageSearchMapperInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Dependency\Service\SalesReturnPageSearchToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Business\DataMapper\ReturnReasonPageSearchDataMapperInterface
     */
    protected $returnReasonPageSearchDataMapper;

    /**
     * @param \Spryker\Zed\SalesReturnPageSearch\Dependency\Service\SalesReturnPageSearchToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\SalesReturnPageSearch\Business\DataMapper\ReturnReasonPageSearchDataMapperInterface $returnReasonPageSearchDataMapper
     */
    public function __construct(
        SalesReturnPageSearchToUtilEncodingServiceInterface $utilEncodingService,
        ReturnReasonPageSearchDataMapperInterface $returnReasonPageSearchDataMapper
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->returnReasonPageSearchDataMapper = $returnReasonPageSearchDataMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonTransfer $returnReasonTransfer
     * @param \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer $returnReasonPageSearchTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string[][] $returnReasonTranslations
     *
     * @return \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer
     */
    public function mapReturnReasonTransferToReturnReasonPageSearchTransfer(
        ReturnReasonTransfer $returnReasonTransfer,
        ReturnReasonPageSearchTransfer $returnReasonPageSearchTransfer,
        LocaleTransfer $localeTransfer,
        array $returnReasonTranslations
    ): ReturnReasonPageSearchTransfer {
        $returnReasonPageSearchTransfer->fromArray($returnReasonTransfer->toArray(), true);
        $returnReasonPageSearchTransfer->setName($returnReasonTranslations[$returnReasonTransfer->getGlossaryKeyReason()][$localeTransfer->getIdLocale()] ?? null);

        $returnReasonPageSearchData = $returnReasonPageSearchTransfer->toArray(true, true);

        $returnReasonPageSearchTransfer->setData(
            $this->returnReasonPageSearchDataMapper->mapReturnReasonDataToSearchData($returnReasonPageSearchData, $localeTransfer)
        );

        unset($returnReasonPageSearchData[ReturnReasonPageSearchTransfer::STRUCTURED_DATA]);

        $returnReasonPageSearchTransfer->setStructuredData(
            $this->utilEncodingService->encodeJson($returnReasonPageSearchData)
        );

        $returnReasonPageSearchTransfer->setLocale($localeTransfer->getLocaleName());

        return $returnReasonPageSearchTransfer;
    }
}

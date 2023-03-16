<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business\Provider;

use Generated\Shared\Transfer\OauthUserTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\WarehouseIdentifierTransfer;
use Spryker\Zed\OauthWarehouse\Business\Reader\StockReaderInterface;
use Spryker\Zed\OauthWarehouse\Dependency\Service\OauthWarehouseToUtilEncodingServiceInterface;

class WarehouseUserProvider implements WarehouseUserProviderInterface
{
    /**
     * @var \Spryker\Zed\OauthWarehouse\Business\Reader\StockReaderInterface
     */
    protected StockReaderInterface $stockReader;

    /**
     * @var \Spryker\Zed\OauthWarehouse\Dependency\Service\OauthWarehouseToUtilEncodingServiceInterface
     */
    protected OauthWarehouseToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\OauthWarehouse\Business\Reader\StockReaderInterface $stockReader
     * @param \Spryker\Zed\OauthWarehouse\Dependency\Service\OauthWarehouseToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        StockReaderInterface $stockReader,
        OauthWarehouseToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->stockReader = $stockReader;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthUserTransfer $oauthUserTransfer
     *
     * @return \Generated\Shared\Transfer\OauthUserTransfer
     */
    public function getOauthWarehouseUser(OauthUserTransfer $oauthUserTransfer): OauthUserTransfer
    {
        $oauthUserTransfer->setIsSuccess(false);

        if (!$oauthUserTransfer->getIdWarehouse()) {
            return $oauthUserTransfer;
        }

        $stockCriteriaFilterTransfer = (new StockCriteriaFilterTransfer())
            ->setIdStock($oauthUserTransfer->getIdWarehouseOrFail());

        if (!$this->stockReader->hasStock($stockCriteriaFilterTransfer)) {
            return $oauthUserTransfer;
        }

        $warehouseIdentifierTransfer = (new WarehouseIdentifierTransfer())->fromArray($oauthUserTransfer->toArray(), true);
        $encodedPayload = $this->utilEncodingService->encodeJson($warehouseIdentifierTransfer->toArray());

        return $oauthUserTransfer
            ->setUserIdentifier($encodedPayload)
            ->setIsSuccess(true);
    }
}

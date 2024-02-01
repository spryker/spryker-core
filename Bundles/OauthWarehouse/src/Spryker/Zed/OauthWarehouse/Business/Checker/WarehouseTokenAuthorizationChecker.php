<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Business\Checker;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestWarehouseTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Spryker\Zed\OauthWarehouse\Business\Reader\StockReaderInterface;
use Spryker\Zed\OauthWarehouse\OauthWarehouseConfig;

class WarehouseTokenAuthorizationChecker implements WarehouseTokenAuthorizationCheckerInterface
{
    /**
     * @var string
     */
    protected const GLUE_REQUEST_USER = 'glueRequestUser';

    /**
     * @var string
     */
    protected const GLUE_REQUEST_WAREHOUSE = 'glueRequestWarehouse';

    /**
     * @var \Spryker\Zed\OauthWarehouse\Business\Reader\StockReaderInterface
     */
    protected StockReaderInterface $stockReader;

    /**
     * @var \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig
     */
    protected OauthWarehouseConfig $oauthWarehouseConfig;

    /**
     * @param \Spryker\Zed\OauthWarehouse\Business\Reader\StockReaderInterface $stockReader
     * @param \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig $oauthWarehouseConfig
     */
    public function __construct(StockReaderInterface $stockReader, OauthWarehouseConfig $oauthWarehouseConfig)
    {
        $this->stockReader = $stockReader;
        $this->oauthWarehouseConfig = $oauthWarehouseConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        if ($this->isUserRequest($authorizationRequestTransfer)) {
            return $this->isAllowedUserScope($authorizationRequestTransfer);
        }

        $glueRequestWarehouseTransfer = $this->findWarehouseRequest($authorizationRequestTransfer);
        if (!$glueRequestWarehouseTransfer) {
            return false;
        }

        return $this->stockReader->hasStock(
            (new StockCriteriaFilterTransfer())->setIdStock($glueRequestWarehouseTransfer->getIdWarehouseOrFail()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    protected function isUserRequest(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        $glueRequestUserTransfer = $authorizationRequestTransfer->getEntityOrFail()->getData()[static::GLUE_REQUEST_USER] ?? null;

        return (bool)$glueRequestUserTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    protected function isAllowedUserScope(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        $allowedUserScopes = $this->oauthWarehouseConfig->getAllowedUserScopes();
        if ($allowedUserScopes === []) {
            return true;
        }

        $glueRequestUserTransfer = $authorizationRequestTransfer->getEntityOrFail()->getData()[static::GLUE_REQUEST_USER];
        foreach ($glueRequestUserTransfer->getScopes() as $scope) {
            if (in_array($scope, $allowedUserScopes, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestWarehouseTransfer|null
     */
    protected function findWarehouseRequest(AuthorizationRequestTransfer $authorizationRequestTransfer): ?GlueRequestWarehouseTransfer
    {
        /** @var \Generated\Shared\Transfer\GlueRequestWarehouseTransfer|null $glueRequestWarehouseTransfer */
        $glueRequestWarehouseTransfer = $authorizationRequestTransfer->getEntityOrFail()->getData()[static::GLUE_REQUEST_WAREHOUSE] ?? null;

        if ($glueRequestWarehouseTransfer instanceof GlueRequestWarehouseTransfer) {
            return $glueRequestWarehouseTransfer;
        }

        return null;
    }
}

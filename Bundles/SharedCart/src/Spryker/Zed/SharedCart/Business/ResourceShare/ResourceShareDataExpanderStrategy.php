<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig;

class ResourceShareDataExpanderStrategy implements ResourceShareDataExpanderStrategyInterface
{
    protected const KEY_ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';
    protected const KEY_ID_COMPANY_USER = 'id_company_user';
    protected const KEY_ID_QUOTE = 'id_quote';

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function expandResourceShareDataWithShareableCartDetails(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareTransfer->setResourceShareData(
            $this->expandResourceShareData($resourceShareTransfer->getResourceShareData())
        );

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareDataTransfer $resourceShareDataTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function expandResourceShareData(ResourceShareDataTransfer $resourceShareDataTransfer): ResourceShareDataTransfer
    {
        $resourceShareDataTransfer = $this->expandResourceShareDataWithShareOption($resourceShareDataTransfer);
        $resourceShareDataTransfer = $this->expandResourceShareDataWithIdQuote($resourceShareDataTransfer);
        $resourceShareDataTransfer = $this->expandResourceShareDataWithIdCompanyUser($resourceShareDataTransfer);
        $resourceShareDataTransfer = $this->expandResourceShareDataWithIdCompanyBusinessUnit($resourceShareDataTransfer);

        return $resourceShareDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareDataTransfer $resourceShareDataTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function expandResourceShareDataWithShareOption(ResourceShareDataTransfer $resourceShareDataTransfer): ResourceShareDataTransfer
    {
        $shareOption = $resourceShareDataTransfer->getData()[SharedCartConfig::KEY_SHARE_OPTION] ?? null;
        $resourceShareDataTransfer->setShareOption($shareOption);

        return $resourceShareDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareDataTransfer $resourceShareDataTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function expandResourceShareDataWithIdQuote(ResourceShareDataTransfer $resourceShareDataTransfer): ResourceShareDataTransfer
    {
        $idQuote = $resourceShareDataTransfer->getData()[static::KEY_ID_QUOTE] ?? null;
        $resourceShareDataTransfer->setIdQuote($idQuote);

        return $resourceShareDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareDataTransfer $resourceShareDataTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function expandResourceShareDataWithIdCompanyUser(ResourceShareDataTransfer $resourceShareDataTransfer): ResourceShareDataTransfer
    {
        $idCompanyUser = $resourceShareDataTransfer->getData()[static::KEY_ID_COMPANY_USER] ?? null;
        $resourceShareDataTransfer->setIdCompanyUser($idCompanyUser);

        return $resourceShareDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareDataTransfer $resourceShareDataTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function expandResourceShareDataWithIdCompanyBusinessUnit(ResourceShareDataTransfer $resourceShareDataTransfer): ResourceShareDataTransfer
    {
        $idCompanyBusinessUnit = $resourceShareDataTransfer->getData()[static::KEY_ID_COMPANY_BUSINESS_UNIT] ?? null;
        $resourceShareDataTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        return $resourceShareDataTransfer;
    }
}

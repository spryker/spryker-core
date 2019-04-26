<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\ResourceShare;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig;

class ResourceShareDataExpanderStrategy implements ResourceShareDataExpanderStrategyInterface
{
    protected const KEY_ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';
    protected const KEY_ID_COMPANY_USER = 'id_company_user';
    protected const KEY_ID_QUOTE = 'id_quote';

    protected const GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING = 'shared_cart.resource_share.strategy.error.properties_are_missing';

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function applyResourceShareDataExpanderStrategy(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareDataTransfer = $this->expandResourceShareData($resourceShareTransfer->getResourceShareData());
        $resourceShareResponseTransfer = $this->validateExpandedResourceShareData($resourceShareDataTransfer);

        if ($resourceShareResponseTransfer->getIsSuccessful()) {
            $resourceShareResponseTransfer->setResourceShare($resourceShareTransfer);
        }

        return $resourceShareResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareDataTransfer $resourceShareDataTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    protected function validateExpandedResourceShareData(ResourceShareDataTransfer $resourceShareDataTransfer): ResourceShareResponseTransfer
    {
        $resourceShareResponseTransfer = new ResourceShareResponseTransfer();

        if ($resourceShareDataTransfer->getShareOption()
            && $resourceShareDataTransfer->getIdQuote()
            && $resourceShareDataTransfer->getIdCompanyUser()
            && $resourceShareDataTransfer->getIdCompanyBusinessUnit()
        ) {
            return $resourceShareResponseTransfer->setIsSuccessful(true);
        }

        return $resourceShareResponseTransfer->setIsSuccessful(false)
            ->addMessage(
                (new MessageTransfer())->setValue(static::GLOSSARY_KEY_ONE_OR_MORE_REQUIRED_PROPERTIES_ARE_MISSING)
            );
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

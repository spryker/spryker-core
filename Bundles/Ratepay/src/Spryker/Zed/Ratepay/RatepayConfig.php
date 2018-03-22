<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay;

use Spryker\Shared\Ratepay\RatepayConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class RatepayConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getTransactionGatewayUrl()
    {
        return $this->get(RatepayConstants::API_URL);
    }

    /**
     * @return string
     */
    public function getProfileId()
    {
        return $this->get(RatepayConstants::PROFILE_ID);
    }

    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->get(RatepayConstants::SECURITY_CODE);
    }

    /**
     * @return string
     */
    public function getSystemId()
    {
        return $this->get(RatepayConstants::SYSTEM_ID);
    }

    /**
     * @return string
     */
    public function getSnippedId()
    {
        return $this->get(RatepayConstants::SNIPPET_ID);
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->get(RatepayConstants::SHOP_ID);
    }

    /**
     * @return string
     */
    public function getTranslationFilePath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . RatepayConstants::GLOSSARY_FILE_PATH;
    }
}

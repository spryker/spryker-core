<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Glue\Testify\Helper;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;
use SprykerTest\Shared\Store\Helper\StoreDataHelperTrait;

class GlueStorefrontApiJsonApiHelper extends AbstractJsonApiHelper
{
    use StoreDataHelperTrait;

    /**
     * @var string
     */
    protected const HEADER_X_REQUESTED_WITH = 'X-Requested-With';

    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig::HEADER_ACCEPT
     *
     * @var string
     */
    protected const HEADER_ACCEPT = 'accept';

    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig::HEADER_CONTENT_TYPE
     *
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'content-type';

    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig::HEADER_ACCEPT_LANGUAGE
     *
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'accept-language';

    /**
     * @var string
     */
    protected const HEADER_STORE_NAME = 'Store';

    /**
     * @var string
     */
    protected const HEADER_X_REQUESTED_WITH_VALUE = 'Codeception';

    /**
     * @uses \Spryker\Glue\GlueJsonApiConvention\GlueJsonApiConventionConfig::HEADER_CONTENT_TYPE
     *
     * @var string
     */
    protected const HEADER_CONTENT_TYPE_VALUE = 'application/vnd.api+json';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT_VALUE = '*/*';

    /**
     * @var string
     */
    protected const DEFAULT_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_LANGUAGE = 'en';

    /**
     * @return void
     */
    protected function prepareHeaders(): void
    {
        $this->startFollowingRedirects();
        $this->haveHttpHeader(static::HEADER_X_REQUESTED_WITH, static::HEADER_X_REQUESTED_WITH_VALUE);
        $this->haveHttpHeader(static::HEADER_CONTENT_TYPE, static::HEADER_CONTENT_TYPE_VALUE);
        $this->haveHttpHeader(static::HEADER_ACCEPT, static::HEADER_ACCEPT_VALUE);

        if (!$this->getStoreDataHelper()->isDynamicStoreEnabled()) {
            return;
        }

        $this->haveHttpHeader(static::HEADER_ACCEPT_LANGUAGE, static::DEFAULT_LANGUAGE);
        $this->haveHttpHeader(static::HEADER_STORE_NAME, static::DEFAULT_STORE_NAME);
    }

    /**
     * @return string
     */
    protected function getApplicationDomain(): string
    {
        return Config::get(TestifyConstants::GLUE_STOREFRONT_API_DOMAIN);
    }
}

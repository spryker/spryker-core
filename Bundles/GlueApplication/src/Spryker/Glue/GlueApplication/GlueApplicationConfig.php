<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;

class GlueApplicationConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getGlueDomainName(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN);
    }

    /**
     * @return bool
     */
    public function getIsRestDebug(): bool
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_REST_DEBUG, false);
    }

    /**
     * @return string
     */
    public function getCorsAllowOrigin(): string
    {
        return $this->get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN);
    }

    /**
     * @return array
     */
    public function getCorsAllowHeaders(): array
    {
        return [
            RequestConstantsInterface::HEADER_ACCEPT,
            RequestConstantsInterface::HEADER_CONTENT_TYPE,
            RequestConstantsInterface::HEADER_CONTENT_LANGUAGE,
            RequestConstantsInterface::HEADER_ACCEPT_LANGUAGE,
            RequestConstantsInterface::HEADER_AUTHORIZATION,
        ];
    }
}

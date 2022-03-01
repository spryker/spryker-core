<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\GlueJsonApiConvention\GlueJsonApiConventionConstants;

class GlueJsonApiConventionConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - JSON:API convention identifier.
     *
     * @api
     *
     * @var string
     */
    public const CONVENTION_JSON_API = 'json_api';

    /**
     * @api
     *
     * @var string
     */
    public const HEADER_CONTENT_TYPE = 'application/vnd.api+json';

    /**
     * Specification:
     * - Domain name of current API application.
     *
     * @api
     *
     * @return string
     */
    public function getGlueDomain(): string
    {
        return $this->get(GlueJsonApiConventionConstants::GLUE_DOMAIN);
    }
}

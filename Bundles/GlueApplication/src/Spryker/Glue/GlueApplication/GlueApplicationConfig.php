<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication;

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
}

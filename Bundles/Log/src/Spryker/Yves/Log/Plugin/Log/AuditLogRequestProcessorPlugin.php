<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log\Plugin\Log;

use Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Log\LogFactory getFactory()
 */
class AuditLogRequestProcessorPlugin extends AbstractPlugin implements LogProcessorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds request related data.
     * - Sanitizes the data based on audit log related configuration.
     * - Uses {@link \Spryker\Yves\Log\LogConfig::getAuditLogSanitizerFieldNames()} to get the field names to sanitize.
     * - Uses {@link \Spryker\Yves\Log\LogConfig::getAuditLogSanitizedFieldValue()} to get the value which is used for fields sanitizing.
     *
     * @api
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $data): array
    {
        return $this->getFactory()->createAuditLogRequestProcessor()->__invoke($data);
    }
}

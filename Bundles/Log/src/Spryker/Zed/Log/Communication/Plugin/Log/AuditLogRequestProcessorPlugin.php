<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Communication\Plugin\Log;

use Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Log\Communication\LogCommunicationFactory getFactory()
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 * @method \Spryker\Zed\Log\Business\LogFacadeInterface getFacade()
 */
class AuditLogRequestProcessorPlugin extends AbstractPlugin implements LogProcessorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds request related data.
     * - Sanitizes the data based on audit log related configuration.
     * - Uses {@link \Spryker\Zed\Log\LogConfig::getAuditLogSanitizerFieldNames()} to get the field names to sanitize.
     * - Uses {@link \Spryker\Zed\Log\LogConfig::getAuditLogSanitizedFieldValue()} to get the value which is used for fields sanitizing.
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

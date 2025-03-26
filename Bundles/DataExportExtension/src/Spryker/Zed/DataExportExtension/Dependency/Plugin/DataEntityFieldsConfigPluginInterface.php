<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExportExtension\Dependency\Plugin;

/**
 * Provides capabilities to create a data export configuration.
 * Is meant to replace the previous data export implementation, which required more code.
 * See docs: https://spryker.atlassian.net/wiki/x/IoBgGQE
 *
 * Use this plugin if you need to export some data objects from the Database.
 */
interface DataEntityFieldsConfigPluginInterface
{
    /**
     * Specification:
     * - Returns an array of fields configuration.
     * - The accepted format is `'field_name_in_export_file:field_name_in_DB'`.
     * - For nested objects, it is `'object_name.*.field_name_in_export_file:object_name.*.field_name_in_DB'`.
     *
     * @api
     *
     * @return array<string>
     */
    public function getFieldsConfig(): array;
}

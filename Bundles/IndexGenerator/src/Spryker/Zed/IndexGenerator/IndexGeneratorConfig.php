<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator;

use Spryker\Shared\IndexGenerator\IndexGeneratorConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class IndexGeneratorConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string[]
     */
    public function getExcludedTables(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTargetDirectory(): string
    {
        $projectNamespace = $this->get(KernelConstants::PROJECT_NAMESPACE);
        $targetPropelSchemaDirectory = implode(DIRECTORY_SEPARATOR, [
            APPLICATION_SOURCE_DIR,
            $projectNamespace,
            'Zed',
            'IndexGenerator',
            'Persistence',
            'Propel',
            'Schema',
        ]);

        return $targetPropelSchemaDirectory;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPathToMergedSchemas(): string
    {
        return implode(DIRECTORY_SEPARATOR, [APPLICATION_SOURCE_DIR, 'Orm', 'Propel', APPLICATION_STORE, 'Schema']);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(IndexGeneratorConstants::PERMISSION_MODE, 0770);
    }
}

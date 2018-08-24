<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class CompanyRoleDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_COMPANY_ROLE = 'company-role';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCompanyRoleDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(
            implode(DIRECTORY_SEPARATOR, [$this->getModuleDataImportDirectory(), 'company_role.csv']),
            static::IMPORT_TYPE_COMPANY_ROLE
        );
    }

    /**
     * @return string
     */
    protected function getModuleDataImportDirectory(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->getModuleRoot(),
            'data',
            'import',
        ]);
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        return realpath(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            '..',
            '..',
            '..',
        ]));
    }
}

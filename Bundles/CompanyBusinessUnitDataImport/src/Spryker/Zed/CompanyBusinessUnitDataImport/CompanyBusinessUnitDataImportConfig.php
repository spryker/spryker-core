<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class CompanyBusinessUnitDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_COMPANY_BUSINESS_UNIT = 'company-business-unit';
    public const IMPORT_TYPE_COMPANY_BUSINESS_UNIT_USER = 'company-business-unit-user';
    public const IMPORT_TYPE_COMPANY_BUSINESS_UNIT_ADDRESS = 'company-business-unit-address';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCompanyBusinessUnitDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(implode(DIRECTORY_SEPARATOR, [$this->getModuleDataImportDirectory(), 'company_business_unit.csv']), static::IMPORT_TYPE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCompanyBusinessUnitUserDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(implode(DIRECTORY_SEPARATOR, [$this->getModuleDataImportDirectory(), 'company_business_unit_user.csv']), static::IMPORT_TYPE_COMPANY_BUSINESS_UNIT_USER);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCompanyBusinessUnitAddressDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration(implode(DIRECTORY_SEPARATOR, [$this->getModuleDataImportDirectory(), 'company_business_unit_address.csv']), static::IMPORT_TYPE_COMPANY_BUSINESS_UNIT_ADDRESS);
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
        ]) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
        );

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}

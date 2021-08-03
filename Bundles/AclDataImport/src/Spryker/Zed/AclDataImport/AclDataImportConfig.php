<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\AclDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class AclDataImportConfig extends DataImportConfig
{
    public const IMPORT_TYPE_ACL_ROLE = 'acl-role';
    public const IMPORT_TYPE_ACL_GROUP = 'acl-group';
    public const IMPORT_TYPE_ACL_GROUP_ROLE = 'acl-group-role';

    protected const FILE_ACL_ROLE = 'acl_role.csv';
    protected const FILE_ACL_GROUP = 'acl_group.csv';
    protected const FILE_ACL_GROUP_ROLE = 'acl_group_role.csv';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getAclRoleDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . static::FILE_ACL_ROLE,
            static::IMPORT_TYPE_ACL_ROLE
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getAclGroupDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . static::FILE_ACL_GROUP,
            static::IMPORT_TYPE_ACL_GROUP
        );
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getAclGroupRoleDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleDataImportDirectory();

        return $this->buildImporterConfiguration(
            $moduleDataImportDirectory . static::FILE_ACL_GROUP_ROLE,
            static::IMPORT_TYPE_ACL_GROUP_ROLE
        );
    }

    /**
     * @return string
     */
    protected function getModuleDataImportDirectory(): string
    {
        return $this->getModuleRoot() . 'data' . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR;
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

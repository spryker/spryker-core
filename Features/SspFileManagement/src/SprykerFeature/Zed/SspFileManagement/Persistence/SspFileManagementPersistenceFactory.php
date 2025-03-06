<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Persistence;

use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper\CompanyBusinessUnitFileMapper;
use SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper\CompanyFileMapper;
use SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper\CompanyUserFileMapper;
use SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper\FileMapper;
use SprykerFeature\Zed\SspFileManagement\Persistence\Saver\FileAttachmentSaverFactory;
use SprykerFeature\Zed\SspFileManagement\SspFileManagementDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementEntityManagerInterface getEntityManager()
 */
class SspFileManagementPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyFileQuery
     */
    public function createCompanyFileQuery(): SpyCompanyFileQuery
    {
        return SpyCompanyFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyUserFileQuery
     */
    public function createCompanyUserFileQuery(): SpyCompanyUserFileQuery
    {
        return SpyCompanyUserFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SspFileManagement\Persistence\SpyCompanyBusinessUnitFileQuery
     */
    public function createCompanyBusinessUnitFileQuery(): SpyCompanyBusinessUnitFileQuery
    {
        return SpyCompanyBusinessUnitFileQuery::create();
    }

    /**
     * @return array<\Propel\Runtime\ActiveQuery\ModelCriteria>
     */
    public function getFileAttachmentQueryList(): array
    {
        return [
            $this->createCompanyFileQuery(),
            $this->createCompanyUserFileQuery(),
            $this->createCompanyBusinessUnitFileQuery(),
        ];
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\Saver\FileAttachmentSaverFactory
     */
    public function createFileAttachmentSaverFactory(): FileAttachmentSaverFactory
    {
        return new FileAttachmentSaverFactory();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper\CompanyFileMapper
     */
    public function createCompanyFileMapper(): CompanyFileMapper
    {
        return new CompanyFileMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper\CompanyUserFileMapper
     */
    public function createCompanyUserFileMapper(): CompanyUserFileMapper
    {
        return new CompanyUserFileMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper\CompanyBusinessUnitFileMapper
     */
    public function createCompanyBusinessUnitFileMapper(): CompanyBusinessUnitFileMapper
    {
        return new CompanyBusinessUnitFileMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\Propel\Mapper\FileMapper
     */
    public function createFileMapper(): FileMapper
    {
        return new FileMapper();
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function getFilePropelQuery(): SpyFileQuery
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::PROPEL_QUERY_FILE);
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function getFileInfoPropelQuery(): SpyFileInfoQuery
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::PROPEL_QUERY_FILE_INFO);
    }
}

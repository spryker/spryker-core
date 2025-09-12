<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetSearchQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetStorageQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToSspModelQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySalesOrderQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelStorageQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\FileMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\ProductClassMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SalesOrderItemMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspAssetBusinessUnitAssignmentMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspAssetMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspInquiryMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspInquiryMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspModelMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspServiceMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Propel\Mapper\SspAssetStorageEntityMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Propel\Mapper\SspAssetStorageEntityMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Propel\Mapper\SspModelStorageEntityMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Propel\Mapper\SspModelStorageEntityMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\QueryBuilder\FileAttachmentQueryBuilder;
use SprykerFeature\Zed\SelfServicePortal\Persistence\QueryBuilder\SspModelQueryBuilder;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\FileAttachmentSaver;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface getEntityManager()
 */
class SelfServicePortalPersistenceFactory extends AbstractPersistenceFactory
{
    public function createProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return SpyProductShipmentTypeQuery::create();
    }

    public function createProductClassQuery(): SpyProductClassQuery
    {
        return SpyProductClassQuery::create();
    }

    public function createSalesProductClassQuery(): SpySalesProductClassQuery
    {
        return SpySalesProductClassQuery::create();
    }

    public function createProductToProductClassQuery(): SpyProductToProductClassQuery
    {
        return SpyProductToProductClassQuery::create();
    }

    public function createSalesOrderItemProductClassQuery(): SpySalesOrderItemProductClassQuery
    {
        return SpySalesOrderItemProductClassQuery::create();
    }

    public function createCompanyUserFileQuery(): SpyCompanyUserFileQuery
    {
        return SpyCompanyUserFileQuery::create();
    }

    public function createCompanyBusinessUnitFileQuery(): SpyCompanyBusinessUnitFileQuery
    {
        return SpyCompanyBusinessUnitFileQuery::create();
    }

    public function createSspAssetFileQuery(): SpySspAssetFileQuery
    {
        return SpySspAssetFileQuery::create();
    }

    public function createSspInquiryQuery(): SpySspInquiryQuery
    {
        return SpySspInquiryQuery::create();
    }

    public function createSspInquiryFileQuery(): SpySspInquiryFileQuery
    {
        return SpySspInquiryFileQuery::create();
    }

    public function createSspInquiryOrderQuery(): SpySspInquirySalesOrderQuery
    {
        return SpySspInquirySalesOrderQuery::create();
    }

    public function createSspInquirySspAssetQuery(): SpySspInquirySspAssetQuery
    {
        return SpySspInquirySspAssetQuery::create();
    }

    public function createSspServiceMapper(): SspServiceMapper
    {
        return new SspServiceMapper($this->getOmsFacade());
    }

    public function createSspInquiryMapper(): SspInquiryMapperInterface
    {
        return new SspInquiryMapper();
    }

    public function createFileMapper(): FileMapper
    {
        return new FileMapper();
    }

    public function createFileAttachmentQueryBuilder(): FileAttachmentQueryBuilder
    {
        return new FileAttachmentQueryBuilder();
    }

    public function createFileAttachmentSaver(): FileAttachmentSaver
    {
        return new FileAttachmentSaver(
            $this->createCompanyBusinessUnitFileQuery(),
            $this->createCompanyUserFileQuery(),
            $this->createSspAssetFileQuery(),
        );
    }

    public function getFilePropelQuery(): SpyFileQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_FILE);
    }

    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    public function getStateMachineItemStatePropelQuery(): SpyStateMachineItemStateQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_STATE_MACHINE_ITEM_STATE);
    }

    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_OMS);
    }

    public function createAssetMapper(): SspAssetMapper
    {
        return new SspAssetMapper(
            $this->getUtilDateTimeService(),
        );
    }

    public function createSspAssetBusinessUnitAssignmentMapper(): SspAssetBusinessUnitAssignmentMapper
    {
        return new SspAssetBusinessUnitAssignmentMapper();
    }

    public function createModelMapper(): SspModelMapper
    {
        return new SspModelMapper(
            $this->getUtilDateTimeService(),
        );
    }

    public function createSspAssetQuery(): SpySspAssetQuery
    {
        return SpySspAssetQuery::create();
    }

    public function createSspModelQuery(): SpySspModelQuery
    {
        return SpySspModelQuery::create();
    }

    public function createSspModelStorageQuery(): SpySspModelStorageQuery
    {
        return SpySspModelStorageQuery::create();
    }

    public function createSspModelStorageEntityMapper(): SspModelStorageEntityMapperInterface
    {
        return new SspModelStorageEntityMapper();
    }

    public function createSspAssetStorageQuery(): SpySspAssetStorageQuery
    {
        return SpySspAssetStorageQuery::create();
    }

    public function createSspAssetStorageEntityMapper(): SspAssetStorageEntityMapperInterface
    {
        return new SspAssetStorageEntityMapper();
    }

    public function createSspModelQueryBuilder(): SspModelQueryBuilder
    {
        return new SspModelQueryBuilder();
    }

    public function createSspAssetToSspModelQuery(): SpySspAssetToSspModelQuery
    {
        return SpySspAssetToSspModelQuery::create();
    }

    public function getSalesOrderItemSspAssetQuery(): SpySalesOrderItemSspAssetQuery
    {
        return SpySalesOrderItemSspAssetQuery::create();
    }

    public function createSspAssetToCompanyBusinessUnitQuery(): SpySspAssetToCompanyBusinessUnitQuery
    {
        return SpySspAssetToCompanyBusinessUnitQuery::create();
    }

    public function getCompanyQuery(): SpyCompanyQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_COMPANY);
    }

    public function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_COMPANY_BUSINESS_UNIT);
    }

    public function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    public function getUtilDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    public function createProductClassMapper(): ProductClassMapper
    {
        return new ProductClassMapper();
    }

    public function createSalesOrderItemMapper(): SalesOrderItemMapper
    {
        return new SalesOrderItemMapper();
    }

    public function createSspAssetSearchPropelQuery(): SpySspAssetSearchQuery
    {
        return new SpySspAssetSearchQuery();
    }
}

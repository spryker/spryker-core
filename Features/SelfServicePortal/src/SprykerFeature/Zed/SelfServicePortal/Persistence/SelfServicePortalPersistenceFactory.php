<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence;

use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySalesProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySalesOrderQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\CompanyBusinessUnitFileMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\CompanyFileMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\CompanyUserFileMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\FileMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\ProductClassMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspAssetFileMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspAssetMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspInquiryMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspInquiryMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspServiceMapper;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\CompanyBusinessUnitFileSaver;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\CompanyFileSaver;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\CompanyUserFileSaver;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\SspAssetFileSaver;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface getEntityManager()
 */
class SelfServicePortalPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery
     */
    public function createProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return SpyProductShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery
     */
    public function createProductClassQuery(): SpyProductClassQuery
    {
        return SpyProductClassQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySalesProductClassQuery
     */
    public function createSalesProductClassQuery(): SpySalesProductClassQuery
    {
        return SpySalesProductClassQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery
     */
    public function createProductToProductClassQuery(): SpyProductToProductClassQuery
    {
        return SpyProductToProductClassQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemProductClassQuery
     */
    public function createSalesOrderItemProductClassQuery(): SpySalesOrderItemProductClassQuery
    {
        return SpySalesOrderItemProductClassQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyFileQuery
     */
    public function createCompanyFileQuery(): SpyCompanyFileQuery
    {
        return SpyCompanyFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyUserFileQuery
     */
    public function createCompanyUserFileQuery(): SpyCompanyUserFileQuery
    {
        return SpyCompanyUserFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyCompanyBusinessUnitFileQuery
     */
    public function createCompanyBusinessUnitFileQuery(): SpyCompanyBusinessUnitFileQuery
    {
        return SpyCompanyBusinessUnitFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFileQuery
     */
    public function createSspAssetFileQuery(): SpySspAssetFileQuery
    {
        return SpySspAssetFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery
     */
    public function createSspInquiryQuery(): SpySspInquiryQuery
    {
        return SpySspInquiryQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryFileQuery
     */
    public function createSspInquiryFileQuery(): SpySspInquiryFileQuery
    {
        return SpySspInquiryFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySalesOrderQuery
     */
    public function createSspInquiryOrderQuery(): SpySspInquirySalesOrderQuery
    {
        return SpySspInquirySalesOrderQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery
     */
    public function createSspInquirySspAssetQuery(): SpySspInquirySspAssetQuery
    {
        return SpySspInquirySspAssetQuery::create();
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
            $this->createSspAssetFileQuery(),
        ];
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\FileAttachmentSaverInterface>
     */
    public function createFileAttachmentSavers(): array
    {
        return [
            $this->createCompanyFileSaver(),
            $this->createCompanyUserFileSaver(),
            $this->createCompanyBusinessUnitFileSaver(),
            $this->createSspAssetFileSaver(),
        ];
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\CompanyFileSaver
     */
    public function createCompanyFileSaver(): CompanyFileSaver
    {
        return new CompanyFileSaver($this->createCompanyFileQuery());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\CompanyUserFileSaver
     */
    public function createCompanyUserFileSaver(): CompanyUserFileSaver
    {
        return new CompanyUserFileSaver($this->createCompanyUserFileQuery());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\CompanyBusinessUnitFileSaver
     */
    public function createCompanyBusinessUnitFileSaver(): CompanyBusinessUnitFileSaver
    {
        return new CompanyBusinessUnitFileSaver($this->createCompanyBusinessUnitFileQuery());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Saver\SspAssetFileSaver
     */
    public function createSspAssetFileSaver(): SspAssetFileSaver
    {
        return new SspAssetFileSaver($this->createSspAssetFileQuery());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspServiceMapper
     */
    public function createSspServiceMapper(): SspServiceMapper
    {
        return new SspServiceMapper($this->getOmsFacade());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspInquiryMapperInterface
     */
    public function createSspInquiryMapper(): SspInquiryMapperInterface
    {
        return new SspInquiryMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\CompanyFileMapper
     */
    public function createCompanyFileMapper(): CompanyFileMapper
    {
        return new CompanyFileMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\CompanyUserFileMapper
     */
    public function createCompanyUserFileMapper(): CompanyUserFileMapper
    {
        return new CompanyUserFileMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspAssetFileMapper
     */
    public function createSspAssetFileMapper(): SspAssetFileMapper
    {
        return new SspAssetFileMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\CompanyBusinessUnitFileMapper
     */
    public function createCompanyBusinessUnitFileMapper(): CompanyBusinessUnitFileMapper
    {
        return new CompanyBusinessUnitFileMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\FileMapper
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
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_FILE);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function getStateMachineItemStatePropelQuery(): SpyStateMachineItemStateQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_STATE_MACHINE_ITEM_STATE);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\SspAssetMapper
     */
    public function createAssetMapper(): SspAssetMapper
    {
        return new SspAssetMapper(
            $this->getUtilDateTimeService(),
        );
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery
     */
    public function createSspAssetQuery(): SpySspAssetQuery
    {
        return SpySspAssetQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySalesOrderItemSspAssetQuery
     */
    public function getSalesOrderItemSspAssetQuery(): SpySalesOrderItemSspAssetQuery
    {
        return SpySalesOrderItemSspAssetQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery
     */
    public function createSspAssetToCompanyBusinessUnitQuery(): SpySspAssetToCompanyBusinessUnitQuery
    {
        return SpySspAssetToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\ProductClassMapper
     */
    public function createProductClassMapper(): ProductClassMapper
    {
        return new ProductClassMapper();
    }
}

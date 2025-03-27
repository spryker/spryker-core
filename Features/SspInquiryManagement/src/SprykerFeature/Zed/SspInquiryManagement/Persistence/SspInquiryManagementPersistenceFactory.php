<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Persistence;

use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryFileQuery;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySalesOrderQuery;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySspAssetQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\Mapper\SspInquiryMapper;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\Mapper\SspInquiryMapperInterface;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquiryManagementPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryQuery
     */
    public function createSspInquiryQuery(): SpySspInquiryQuery
    {
        return SpySspInquiryQuery::create();
    }

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function createCustomerQuery(): SpyCustomerQuery
    {
        return SpyCustomerQuery::create();
    }

    /**
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiryFileQuery
     */
    public function createSspInquiryFileQuery(): SpySspInquiryFileQuery
    {
        return SpySspInquiryFileQuery::create();
    }

    /**
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySalesOrderQuery
     */
    public function createSspInquiryOrderQuery(): SpySspInquirySalesOrderQuery
    {
        return SpySspInquirySalesOrderQuery::create();
    }

    /**
     * @return \SprykerFeature\Zed\SspInquiryManagement\Persistence\Mapper\SspInquiryMapperInterface
     */
    public function createSspInquiryMapper(): SspInquiryMapperInterface
    {
        return new SspInquiryMapper();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function createStateMachineItemStateQuery(): SpyStateMachineItemStateQuery
    {
        return SpyStateMachineItemStateQuery::create();
    }

    /**
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySspAssetQuery
     */
    public function createSspInquirySspAssetQuery(): SpySspInquirySspAssetQuery
    {
        return SpySspInquirySspAssetQuery::create();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerApiTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\CustomerApi\Business\Mapper\EntityMapperInterface;
use Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface;
use Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface;
use Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface;

class CustomerApi implements CustomerApiInterface
{

    /**
     * @var \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @var \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CustomerApi\Business\Mapper\EntityMapperInterface
     */
    protected $entityMapper;

    /**
     * @var \Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface $apiQueryContainer
     * @param \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CustomerApi\Business\Mapper\EntityMapperInterface $entityMapper
     * @param \Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface $transferMapper
     */
    public function __construct(
        CustomerApiToApiInterface $apiQueryContainer,
        CustomerApiQueryContainerInterface $queryContainer,
        EntityMapperInterface $entityMapper,
        TransferMapperInterface $transferMapper
    ) {
        $this->apiQueryContainer = $apiQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->entityMapper = $entityMapper;
        $this->transferMapper = $transferMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaTransfer = $this->buildPropelQueryBuilderCriteria($apiRequestTransfer);
        $query = $this->buildQuery($apiRequestTransfer, $criteriaTransfer);

        $collection = $this->transferMapper->toTransferCollection(
            $query->find()->toArray()
        );

        return $this->apiQueryContainer->createApiCollection($collection);
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($idCustomer, ApiFilterTransfer $apiFilterTransfer)
    {
        $customerData = $this->getCustomerData($idCustomer, $apiFilterTransfer);
        $customerTransfer = $this->transferMapper->toTransfer($customerData);

        return $this->apiQueryContainer->createApiItem($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        $customerApiTransfer = $this->persist($apiDataTransfer);

        return $this->apiQueryContainer->createApiItem($customerApiTransfer);
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update($idCustomer, ApiDataTransfer $apiDataTransfer)
    {
        $customerApiTransfer = $this->persist($apiDataTransfer);

        return $this->apiQueryContainer->createApiItem($customerApiTransfer);
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function delete($idCustomer)
    {
        $deletedRows = $this->queryContainer
            ->queryCustomerById($idCustomer)
            ->delete();

        $customerApiTransfer = new CustomerApiTransfer();

        if ($deletedRows > 0) {
            $customerApiTransfer->setIdCustomer($idCustomer);
        }

        return $this->apiQueryContainer->createApiItem($customerApiTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerApiTransfer
     */
    protected function persist(ApiDataTransfer $apiDataTransfer)
    {
        $customerEntity = $this->entityMapper->toEntity($apiDataTransfer->getData());
        $customerEntity->save();

        return $this->transferMapper->toTransfer($customerEntity->toArray());
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return array
     */
    protected function getCustomerData($idCustomer, ApiFilterTransfer $apiFilterTransfer)
    {
        $customerEntity = (array)$this->queryContainer
            ->queryCustomerById($idCustomer, $apiFilterTransfer->getFields())
            ->findOne();

        if (!$customerEntity) {
            throw new EntityNotFoundException('Customer not found: ' . $idCustomer);
        }

        return $customerEntity;
    }

    /**
     * TODO invert this, and put it into Api bundle
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function buildPropelQueryBuilderCriteria(ApiRequestTransfer $apiRequestTransfer)
    {
        $criteriaRuleSet = $this->apiQueryContainer->createPropelQueryBuilderCriteriaFromJson(
            $apiRequestTransfer->getFilter()->getFilter()
        );

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($criteriaRuleSet);

        return $criteriaTransfer;
    }

    /**
     * TODO invert this, and put it into Api bundle
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer $criteriaTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function buildQuery(ApiRequestTransfer $apiRequestTransfer, PropelQueryBuilderCriteriaTransfer $criteriaTransfer)
    {
        $query = $this->queryContainer->queryFind($apiRequestTransfer->getFilter()->getFields());
        $query = $this->apiQueryContainer->createQuery($query, $criteriaTransfer);
        $query = $this->apiQueryContainer->mapPagination($query, $apiRequestTransfer->getFilter()->getPagination());

        return $query;
    }

}

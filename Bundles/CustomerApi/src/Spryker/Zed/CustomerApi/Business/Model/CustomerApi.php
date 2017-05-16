<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\CustomerApi\Business\Mapper\EntityMapperInterface;
use Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface;
use Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToCustomerInterface;
use Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface;
use Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiQueryBuilderInterface;
use Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface;

class CustomerApi implements CustomerApiInterface
{

    /**
     * @var \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface
     */
    protected $apiQueryContainer;

    /**
     * @var \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiQueryBuilderInterface
     */
    protected $apiQueryBuilderQueryContainer;

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
     * @var \Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToCustomerInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiInterface $apiQueryContainer
     * @param \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer
     * @param \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CustomerApi\Business\Mapper\EntityMapperInterface $entityMapper
     * @param \Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface $transferMapper
     */
    public function __construct(
        CustomerApiToApiInterface $apiQueryContainer,
        CustomerApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer,
        CustomerApiQueryContainerInterface $queryContainer,
        EntityMapperInterface $entityMapper,
        TransferMapperInterface $transferMapper,
        CustomerApiToCustomerInterface $customerFacade
    ) {
        $this->apiQueryContainer = $apiQueryContainer;
        $this->apiQueryBuilderQueryContainer = $apiQueryBuilderQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->entityMapper = $entityMapper;
        $this->transferMapper = $transferMapper;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        $data = (array)$apiDataTransfer->getData();

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($data, true);

        $customerResponseTransfer = $this->customerFacade->addCustomer($customerTransfer);

        $customerTransfer = $customerResponseTransfer->getCustomerTransfer();

        return $this->apiQueryContainer->createApiItem($customerTransfer, $customerTransfer->getIdCustomer());
    }

    /**
     * @param int $idCustomer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($idCustomer)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        $customerTransfer = $this->customerFacade->findCustomerById($customerTransfer);
        if (!$customerTransfer) {
            throw new EntityNotFoundException(sprintf('Customer not found for id %s', $idCustomer));
        }

        return $this->apiQueryContainer->createApiItem($customerTransfer, $idCustomer);
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update($idCustomer, ApiDataTransfer $apiDataTransfer)
    {
        $entityToUpdate = $this->queryContainer
            ->queryFind()
            ->filterByIdCustomer($idCustomer)
            ->findOne();

        if (!$entityToUpdate) {
            throw new EntityNotFoundException(sprintf('Customer not found: %s', $idCustomer));
        }

        $data = (array)$apiDataTransfer->getData();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        $data = $this->processSecureFields($data);
        $customerTransfer->fromArray($data, true);

        $customerResponseTransfer = $this->customerFacade->updateCustomer($customerTransfer);

        $customerTransfer = $customerResponseTransfer->getCustomerTransfer();
        return $this->apiQueryContainer->createApiItem($customerTransfer, $idCustomer);
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function remove($idCustomer)
    {
        $entityToUpdate = $this->queryContainer
            ->queryFind()
            ->filterByIdCustomer($idCustomer)
            ->findOne();

        if (!$entityToUpdate) {
            return $this->apiQueryContainer->createApiItem([]);
        }

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);
        $customerTransfer->requireIdCustomer();

        $isSuccess = $this->customerFacade->deleteCustomer($customerTransfer);
        if (!$isSuccess) {
            $idCustomer = null;
        }

        return $this->apiQueryContainer->createApiItem([], $idCustomer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        $query = $this->buildQuery($apiRequestTransfer);

        $collection = $this->transferMapper->toTransferCollection(
            $query->find()->toArray()
        );

        foreach ($collection as $k => $productAbstractTransfer) {
            $collection[$k] = $this->get($productAbstractTransfer->getIdCustomer())->getData();
        }

        return $this->apiQueryContainer->createApiCollection($collection);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function buildQuery(ApiRequestTransfer $apiRequestTransfer)
    {
        $apiQueryBuilderQueryTransfer = $this->buildApiQueryBuilderQuery($apiRequestTransfer);

        $query = $this->queryContainer->queryFind();
        $query = $this->apiQueryBuilderQueryContainer->buildQueryFromRequest($query, $apiQueryBuilderQueryTransfer);

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer
     */
    protected function buildApiQueryBuilderQuery(ApiRequestTransfer $apiRequestTransfer)
    {
        $columnSelectionTransfer = $this->buildColumnSelection();

        $apiQueryBuilderQueryTransfer = new ApiQueryBuilderQueryTransfer();
        $apiQueryBuilderQueryTransfer->setApiRequest($apiRequestTransfer);
        $apiQueryBuilderQueryTransfer->setColumnSelection($columnSelectionTransfer);

        return $apiQueryBuilderQueryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer
     */
    protected function buildColumnSelection()
    {
        $columnSelectionTransfer = new PropelQueryBuilderColumnSelectionTransfer();
        $tableColumns = SpyCustomerTableMap::getFieldNames(TableMap::TYPE_FIELDNAME);

        foreach ($tableColumns as $columnAlias) {
            $columnTransfer = new PropelQueryBuilderColumnTransfer();
            $columnTransfer->setName(SpyCustomerTableMap::TABLE_NAME . '.' . $columnAlias);
            $columnTransfer->setAlias($columnAlias);

            $columnSelectionTransfer->addTableColumn($columnTransfer);
        }

        return $columnSelectionTransfer;
    }

    /**
     * Overwrite on project level to allow patching of secure fields.
     *
     * Customize to allow hashing and storing of password or registration key fields.
     *
     * @param array $data
     *
     * @return array
     */
    protected function processSecureFields(array $data)
    {
        unset($data['password'], $data['registration_key']);

        return $data;
    }

}

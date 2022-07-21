<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Model;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiValidationErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\CustomerApi\Business\Mapper\EntityMapperInterface;
use Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface;
use Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToApiFacadeInterface;
use Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToCustomerInterface;
use Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiQueryBuilderInterface;
use Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface;

class CustomerApi implements CustomerApiInterface
{
    /**
     * @var string
     */
    protected const KEY_ID = 'id';

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
     * @var \Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToApiFacadeInterface
     */
    protected $apiFacade;

    /**
     * @param \Spryker\Zed\CustomerApi\Dependency\QueryContainer\CustomerApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer
     * @param \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CustomerApi\Business\Mapper\EntityMapperInterface $entityMapper
     * @param \Spryker\Zed\CustomerApi\Business\Mapper\TransferMapperInterface $transferMapper
     * @param \Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToCustomerInterface $customerFacade
     * @param \Spryker\Zed\CustomerApi\Dependency\Facade\CustomerApiToApiFacadeInterface $apiFacade
     */
    public function __construct(
        CustomerApiToApiQueryBuilderInterface $apiQueryBuilderQueryContainer,
        CustomerApiQueryContainerInterface $queryContainer,
        EntityMapperInterface $entityMapper,
        TransferMapperInterface $transferMapper,
        CustomerApiToCustomerInterface $customerFacade,
        CustomerApiToApiFacadeInterface $apiFacade
    ) {
        $this->apiQueryBuilderQueryContainer = $apiQueryBuilderQueryContainer;
        $this->queryContainer = $queryContainer;
        $this->entityMapper = $entityMapper;
        $this->transferMapper = $transferMapper;
        $this->customerFacade = $customerFacade;
        $this->apiFacade = $apiFacade;
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

        return $this->createApiItemFromCustomerResponseTransfer($customerResponseTransfer);
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($idCustomer)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        $customerTransfer = $this->customerFacade->findCustomerById($customerTransfer);
        if (!$customerTransfer) {
            return $this->createCustomerNotFoundApiItemTransfer($idCustomer);
        }

        return $this->apiFacade->createApiItem($customerTransfer, (string)$idCustomer);
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
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
            return $this->createCustomerNotFoundApiItemTransfer($idCustomer);
        }

        $data = (array)$apiDataTransfer->getData();
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);

        $data = $this->processSecureFields($data);
        $customerTransfer->fromArray($data, true);

        $customerResponseTransfer = $this->customerFacade->updateCustomer($customerTransfer);

        return $this->createApiItemFromCustomerResponseTransfer($customerResponseTransfer, (string)$idCustomer);
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
            return $this->apiFacade->createApiItem();
        }

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($idCustomer);
        $customerTransfer->requireIdCustomer();

        $isSuccess = $this->customerFacade->deleteCustomer($customerTransfer);
        if (!$isSuccess) {
            $idCustomer = null;
        }

        return $this->apiFacade->createApiItem($customerTransfer, (string)$idCustomer);
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
            $query->find()->toArray(),
        );

        $apiCollectionTransfer = $this->apiFacade->createApiCollection($collection);

        $apiCollectionTransfer = $this->addPagination($query, $apiCollectionTransfer, $apiRequestTransfer);

        return $apiCollectionTransfer;
    }

    /**
     * //FIXME: Move to generic place
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiCollectionTransfer $apiCollectionTransfer
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    protected function addPagination(ModelCriteria $query, ApiCollectionTransfer $apiCollectionTransfer, ApiRequestTransfer $apiRequestTransfer)
    {
        $query->setOffset(0);
        $query->setLimit(-1);
        $total = $query->count();

        $apiFilterTransfer = $apiRequestTransfer->getFilter();
        if ($apiFilterTransfer !== null) {
            $page = $apiFilterTransfer->getLimit() ? ($apiFilterTransfer->getOffset() / $apiFilterTransfer->getLimit() + 1) : 1;
            $pageTotal = ($total && $apiFilterTransfer->getLimit()) ? (int)ceil($total / $apiFilterTransfer->getLimit()) : 1;

            $apiPaginationTransfer = (new ApiPaginationTransfer())
                ->setItemsPerPage($apiFilterTransfer->getLimit())
                ->setPage($page)
                ->setTotal($total)
                ->setPageTotal($pageTotal);

            $apiCollectionTransfer->setPagination($apiPaginationTransfer);
        }

        return $apiCollectionTransfer;
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
     * @param array<string, mixed> $data
     *
     * @return array
     */
    protected function processSecureFields(array $data)
    {
        unset($data['password'], $data['registration_key']);

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string|null $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    protected function createApiItemFromCustomerResponseTransfer(
        CustomerResponseTransfer $customerResponseTransfer,
        ?string $idCustomer = null
    ): ApiItemTransfer {
        if ($customerResponseTransfer->getIsSuccess()) {
            $customerTransfer = $customerResponseTransfer->getCustomerTransferOrFail();

            return $this->apiFacade->createApiItem(
                $customerTransfer,
                (string)$customerTransfer->getIdCustomerOrFail(),
            );
        }

        $apiItemTransfer = $this->apiFacade->createApiItem(
            $customerResponseTransfer->getCustomerTransfer(),
            $idCustomer,
        );

        foreach ($customerResponseTransfer->getErrors() as $customerErrorTransfer) {
            $apiValidationErrorTransfer = $this->createApiValidationErrorTransfer($customerErrorTransfer->getMessageOrFail());

            $apiItemTransfer->addValidationError($apiValidationErrorTransfer);
        }

        return $apiItemTransfer;
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    protected function createCustomerNotFoundApiItemTransfer(int $idCustomer): ApiItemTransfer
    {
        $apiValidationErrorTransfer = $this->createApiValidationErrorTransfer(
            sprintf('Customer not found: %s', $idCustomer),
            static::KEY_ID,
        );

        return $this->apiFacade
            ->createApiItem(null, (string)$idCustomer)
            ->setStatusCode(ApiConfig::HTTP_CODE_NOT_FOUND)
            ->addValidationError($apiValidationErrorTransfer);
    }

    /**
     * @param string $message
     * @param string|null $field
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer
     */
    protected function createApiValidationErrorTransfer(string $message, ?string $field = null): ApiValidationErrorTransfer
    {
        return (new ApiValidationErrorTransfer())
            ->setField($field)
            ->addMessages($message);
    }
}

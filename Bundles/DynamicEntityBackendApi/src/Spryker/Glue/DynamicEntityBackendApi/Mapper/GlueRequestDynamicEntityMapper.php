<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Mapper;

use Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldConditionTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Symfony\Component\HttpFoundation\Request;

class GlueRequestDynamicEntityMapper
{
    /**
     * @var string
     */
    protected const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const DYNAMIC_ENTITY_PATH_PATTERN = '/\/([^\/]+)\/([\w-]+)/';

    /**
     * @var string
     */
    protected const QUERY_PARAMETER_INCLUDE = 'include';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface
     */
    protected DynamicEntityBackendApiToUtilEncodingServiceInterface $serviceUtilEncoding;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig
     */
    protected DynamicEntityBackendApiConfig $config;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface $serviceUtilEncoding
     * @param \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig $config
     */
    public function __construct(DynamicEntityBackendApiToUtilEncodingServiceInterface $serviceUtilEncoding, DynamicEntityBackendApiConfig $config)
    {
        $this->serviceUtilEncoding = $serviceUtilEncoding;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param string|null $id
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer
     */
    public function mapGlueRequestToDynamicEntityCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        ?string $id = null
    ): DynamicEntityCriteriaTransfer {
        $dynamicEntityCriteriaTransfer = new DynamicEntityCriteriaTransfer();

        $paginationTransfer = $this->setDefaultPaginationLimit($glueRequestTransfer->getPagination());

        $queryFields = $glueRequestTransfer->getQueryFields();

        if (isset($queryFields[static::QUERY_PARAMETER_INCLUDE])) {
            $dynamicEntityCriteriaTransfer->setRelationChains(
                explode(',', $queryFields[static::QUERY_PARAMETER_INCLUDE]),
            );
        }

        $dynamicEntityCriteriaTransfer->setPagination($paginationTransfer);

        $dynamicEntityConditionsTransfer = $this->createDynamicEntityConditionsTransfer($glueRequestTransfer, $id);
        $dynamicEntityCriteriaTransfer->setDynamicEntityConditions($dynamicEntityConditionsTransfer);

        return $dynamicEntityCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer
     */
    public function mapGlueRequestTransferToDynamicEntityCollectionDeleteCriteriaTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
    ): DynamicEntityCollectionDeleteCriteriaTransfer {
        $dynamicEntityConditionsTransfer = $this->createDynamicEntityConditionsTransfer(
            $glueRequestTransfer,
            $glueRequestTransfer->getResourceOrFail()->getId(),
        );

        return $dynamicEntityCollectionDeleteCriteriaTransfer
            ->setDynamicEntityConditions($dynamicEntityConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer|null
     */
    public function mapGlueRequestToDynamicEntityCollectionRequestTransfer(
        GlueRequestTransfer $glueRequestTransfer
    ): ?DynamicEntityCollectionRequestTransfer {
        $dynamicEntityCollectionRequestTransfer = $this->createDynamicEntityCollectionRequestTransfer($glueRequestTransfer);

        if ($glueRequestTransfer->getContent() === null) {
            return null;
        }

        /** @var array<string,mixed>|null $decodedContent */
        $decodedContent = $this->serviceUtilEncoding->decodeJson($glueRequestTransfer->getContent(), true);
        $dataCollection = $decodedContent['data'] ?? null;

        if ($dataCollection === null || $dataCollection === []) {
            return null;
        }

        if ($glueRequestTransfer->getResourceOrFail()->getId() !== null) {
            return $this->mapContentForIdRequest($dataCollection, $glueRequestTransfer, $dynamicEntityCollectionRequestTransfer);
        }

        return $this->mapContentForCollectionRequest($dataCollection, $dynamicEntityCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    protected function createDynamicEntityCollectionRequestTransfer(GlueRequestTransfer $glueRequestTransfer): DynamicEntityCollectionRequestTransfer
    {
        $dynamicEntityCollectionRequestTransfer = (new DynamicEntityCollectionRequestTransfer())
            ->setTableAlias(
                $this->extractTableAlias($glueRequestTransfer->getPathOrFail()),
            );

        $httpMethod = $glueRequestTransfer->getResourceOrFail()->getMethod();
        if ($httpMethod === Request::METHOD_POST || $httpMethod === Request::METHOD_PUT) {
            $dynamicEntityCollectionRequestTransfer->setIsCreatable(true);
        }

        if ($httpMethod === Request::METHOD_PUT) {
            $dynamicEntityCollectionRequestTransfer->setResetNotProvidedFieldValues(true);
        }

        $dynamicEntityCollectionRequestTransfer->setIsTransactional(
            $this->isTransactionalRequest($glueRequestTransfer),
        );

        return $dynamicEntityCollectionRequestTransfer;
    }

    /**
     * @param array<mixed> $dataCollection
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer|null
     */
    protected function mapContentForIdRequest(
        array $dataCollection,
        GlueRequestTransfer $glueRequestTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): ?DynamicEntityCollectionRequestTransfer {
        if ($this->isAssociativeArray($dataCollection) === false) {
            return null;
        }

        $dataCollection[static::IDENTIFIER] = $glueRequestTransfer->getResourceOrFail()->getId();

        return $this->mapRequestContentToDynamicEntityTransfer(
            $dynamicEntityCollectionRequestTransfer,
            $dataCollection,
            $glueRequestTransfer->getResourceOrFail()->getId(),
        );
    }

    /**
     * @param array<mixed> $dataCollection
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer|null
     */
    protected function mapContentForCollectionRequest(
        array $dataCollection,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): ?DynamicEntityCollectionRequestTransfer {
        foreach ($dataCollection as $item) {
            if (!is_array($item)) {
                return null;
            }

            $dynamicEntityCollectionRequestTransfer = $this->mapRequestContentToDynamicEntityTransfer($dynamicEntityCollectionRequestTransfer, $item);
        }

        return $dynamicEntityCollectionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param array<mixed> $fields
     * @param string|null $identifier
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer
     */
    protected function mapRequestContentToDynamicEntityTransfer(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        array $fields,
        ?string $identifier = null
    ): DynamicEntityCollectionRequestTransfer {
        $dynamicEntityTransfer = $this
            ->mapChildRelationsToDynamicEntityTransfer($fields)
            ->setFields($fields);

        if ($identifier !== null) {
            $dynamicEntityTransfer->setIdentifier($identifier);
        }

        $dynamicEntityCollectionRequestTransfer->addDynamicEntity($dynamicEntityTransfer);

        return $dynamicEntityCollectionRequestTransfer;
    }

    /**
     * @param array<mixed> $fields
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer|null $childDynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityRelationTransfer|null $childRelation
     * @param array<mixed> $childRelations
     *
     * @return \Generated\Shared\Transfer\DynamicEntityTransfer
     */
    protected function mapChildRelationsToDynamicEntityTransfer(
        array $fields,
        ?DynamicEntityTransfer $childDynamicEntityTransfer = null,
        ?DynamicEntityRelationTransfer $childRelation = null,
        array $childRelations = []
    ): DynamicEntityTransfer {
        $dynamicEntityTransfer = new DynamicEntityTransfer();

        foreach ($fields as $fieldName => $fieldValue) {
            if (is_array($fieldValue) === false) {
                continue;
            }

            if (!is_int($fieldName)) {
                $childRelation = $childRelations[$fieldName] ?? (new DynamicEntityRelationTransfer())->setName($fieldName);
                $childRelations[$fieldName] = $childRelation;

                $this->mapChildRelationsToDynamicEntityTransfer($fieldValue, null, $childRelation, $childRelations);

                $dynamicEntityTransfer = $childDynamicEntityTransfer ?? $dynamicEntityTransfer;
                $dynamicEntityTransfer->addChildRelation($childRelation);

                continue;
            }

            $childDynamicEntity = (new DynamicEntityTransfer())->setFields($fieldValue);
            $childRelation->addDynamicEntity($childDynamicEntity);
            $childRelations[$childRelation->getName()] = $childRelation;

            $this->mapChildRelationsToDynamicEntityTransfer($fieldValue, $childDynamicEntity, $childRelation, $childRelations);
        }

        return $dynamicEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param string|null $id
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConditionsTransfer
     */
    protected function createDynamicEntityConditionsTransfer(
        GlueRequestTransfer $glueRequestTransfer,
        ?string $id = null
    ): DynamicEntityConditionsTransfer {
        $dynamicEntityConditionsTransfer = (new DynamicEntityConditionsTransfer())
            ->setTableAlias($this->extractTableAlias($glueRequestTransfer->getPathOrFail()));

        if ($id !== null) {
            $dynamicEntityConditionsTransfer->addFieldCondition(
                (new DynamicEntityFieldConditionTransfer())
                    ->setName(static::IDENTIFIER)
                    ->setValue($id),
            );
        }

        foreach ($glueRequestTransfer->getFilters() as $filter) {
            $dynamicEntityConditionsTransfer->addFieldCondition(
                (new DynamicEntityFieldConditionTransfer())
                    ->setName($filter->getField())
                    ->setValue($filter->getValue()),
            );
        }

        return $dynamicEntityConditionsTransfer;
    }

    /**
     * @param string $string
     *
     * @return string|null
     */
    protected function extractTableAlias(string $string): ?string
    {
        $matches = [];

        if (preg_match(static::DYNAMIC_ENTITY_PATH_PATTERN, $string, $matches)) {
            return $matches[2];
        }

        return null;
    }

    /**
     * @param array<mixed> $array
     *
     * @return bool
     */
    protected function isAssociativeArray(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function setDefaultPaginationLimit(?PaginationTransfer $paginationTransfer): PaginationTransfer
    {
        if ($paginationTransfer === null) {
            $paginationTransfer = new PaginationTransfer();
            $paginationTransfer->setLimit($this->config->getDefaultPaginationLimit());

            return $paginationTransfer;
        }

        if ($paginationTransfer->getLimit() === null) {
            $paginationTransfer->setLimit($this->config->getDefaultPaginationLimit());
        }

        return $paginationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    protected function isTransactionalRequest(GlueRequestTransfer $glueRequestTransfer): bool
    {
        $transactionalHeader = strtolower($this->config->getTransactionalHeader());
        $meta = $glueRequestTransfer->getMeta();
        if (!isset($meta[$transactionalHeader]) || $meta[$transactionalHeader] === []) {
            return true;
        }

        $isTransactional = $meta[$transactionalHeader][0];
        if ($isTransactional === 'false' || $isTransactional === '0') {
            return false;
        }

        return true;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Search\Expander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Permission\PermissionClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use SprykerFeature\Client\SelfServicePortal\Builder\PaginationConfigBuilderInterface;
use SprykerFeature\Client\SelfServicePortal\Builder\SortConfigBuilderInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig;

class SspAssetSearchQueryExpander implements SspAssetSearchQueryExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_OFFSET = 'offset';

    /**
     * @var string
     */
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * @var string
     */
    protected const TERM_ID_OWNER_BUSINESS_UNIT = 'id_owner_business_unit';

    /**
     * @var string
     */
    protected const TERM_COMPANY_IDS = 'company-ids';

    /**
     * @var string
     */
    protected const TERM_ID_OWNER_COMPANY_ID = 'id_owner_company_id';

    /**
     * @var string
     */
    protected const TERM_BUSINESS_UNIT_IDS = 'business-unit-ids';

    public function __construct(
        protected CompanyUserClientInterface $companyUserClient,
        protected PermissionClientInterface $permissionClient,
        protected SelfServicePortalConfig $config
    ) {
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     * @param \SprykerFeature\Client\SelfServicePortal\Builder\PaginationConfigBuilderInterface $paginationConfigBuilder
     * @param \SprykerFeature\Client\SelfServicePortal\Builder\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(
        QueryInterface $searchQuery,
        array $requestParameters,
        PaginationConfigBuilderInterface $paginationConfigBuilder,
        SortConfigBuilderInterface $sortConfigBuilder
    ): QueryInterface {
        $query = $searchQuery->getSearchQuery();

        if (!$query instanceof Query) {
            return $searchQuery;
        }

        if ($query->getQuery() instanceof BoolQuery) {
            $this->addPermissionBasedFiltering($query->getQuery(), $requestParameters);
        }

        $this->addPaginationToQuery($searchQuery->getSearchQuery(), $requestParameters, $paginationConfigBuilder);
        $this->addSorting($query, $requestParameters, $sortConfigBuilder);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query\BoolQuery $query
     * @param array<string, mixed> $requestParameters
     *
     * @return void
     */
    protected function addPermissionBasedFiltering(BoolQuery $query, array $requestParameters): void
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();

        if (!$companyUserTransfer) {
            return;
        }

        $mainOrQuery = new BoolQuery();
        $userBusinessUnitId = $companyUserTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail();
        $userCompanyId = $companyUserTransfer->getCompanyOrFail()->getIdCompanyOrFail();

        $this->addOwnerBasedFiltering($mainOrQuery, $userBusinessUnitId);
        $this->addCompanyLevelPermissionFiltering($mainOrQuery, $userCompanyId);
        $this->addBusinessUnitLevelPermissionFiltering($mainOrQuery, $userBusinessUnitId);

        $query->addFilter($mainOrQuery);
    }

    protected function addOwnerBasedFiltering(BoolQuery $mainOrQuery, int $userBusinessUnitId): void
    {
        $ownerBusinessUnitQuery = new Term([static::TERM_ID_OWNER_BUSINESS_UNIT => $userBusinessUnitId]);
        $mainOrQuery->addShould($ownerBusinessUnitQuery);
    }

    protected function addCompanyLevelPermissionFiltering(BoolQuery $mainOrQuery, int $userCompanyId): void
    {
        if (!$this->hasViewCompanySspAssetPermission()) {
            return;
        }

        $companyOrQuery = new BoolQuery();

        $this->addCompanyAssignedAssetsFilter($companyOrQuery, $userCompanyId);
        $this->addCompanyOwnedAssetsFilter($companyOrQuery, $userCompanyId);

        $mainOrQuery->addShould($companyOrQuery);
    }

    protected function addCompanyAssignedAssetsFilter(BoolQuery $companyOrQuery, int $userCompanyId): void
    {
        $companyAssignedQuery = new Terms(static::TERM_COMPANY_IDS, [$userCompanyId]);
        $companyOrQuery->addShould($companyAssignedQuery);
    }

    protected function addCompanyOwnedAssetsFilter(BoolQuery $companyOrQuery, int $userCompanyId): void
    {
        $ownerCompanyQuery = new Term([static::TERM_ID_OWNER_COMPANY_ID => $userCompanyId]);
        $companyOrQuery->addShould($ownerCompanyQuery);
    }

    protected function addBusinessUnitLevelPermissionFiltering(BoolQuery $mainOrQuery, int $userBusinessUnitId): void
    {
        if (!$this->hasViewBusinessUnitSspAssetPermission()) {
            return;
        }

        $businessUnitQuery = new Terms(static::TERM_BUSINESS_UNIT_IDS, [$userBusinessUnitId]);
        $mainOrQuery->addShould($businessUnitQuery);
    }

    /**
     * @param \Elastica\Query $query
     * @param array<string, mixed> $requestParameters
     * @param \SprykerFeature\Client\SelfServicePortal\Builder\PaginationConfigBuilderInterface $paginationConfigBuilder
     *
     * @return void
     */
    protected function addPaginationToQuery(Query $query, array $requestParameters, PaginationConfigBuilderInterface $paginationConfigBuilder): void
    {
        if (isset($requestParameters[static::PARAMETER_OFFSET]) && isset($requestParameters[static::PARAMETER_LIMIT])) {
            $query->setFrom($requestParameters[static::PARAMETER_OFFSET]);
            $query->setSize($requestParameters[static::PARAMETER_LIMIT]);

            return;
        }

        $currentPage = $paginationConfigBuilder->getCurrentPage($requestParameters);
        $itemsPerPage = $paginationConfigBuilder->getCurrentItemsPerPage($requestParameters);

        $query->setFrom(($currentPage - 1) * $itemsPerPage);
        $query->setSize($itemsPerPage);
    }

    /**
     * @param \Elastica\Query $searchQuery
     * @param array<string, mixed> $requestParameters
     * @param \SprykerFeature\Client\SelfServicePortal\Builder\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return void
     */
    protected function addSorting(Query $searchQuery, array $requestParameters, SortConfigBuilderInterface $sortConfigBuilder): void
    {
        $sortConfigTransfer = $this->getSortConfigTransfer($sortConfigBuilder, $requestParameters);
        $sortField = sprintf('%s.%s', $sortConfigTransfer->getFieldName(), $sortConfigTransfer->getName());
        $sortDirection = $sortConfigBuilder->getSortDirection($sortConfigTransfer->getParameterNameOrFail());

        $searchQuery->addSort([
            $sortField => [
                'order' => $sortDirection,
            ],
        ]);
    }

    /**
     * @param \SprykerFeature\Client\SelfServicePortal\Builder\SortConfigBuilderInterface $sspAssetSearchSortConfigBuilder
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    protected function getSortConfigTransfer(
        SortConfigBuilderInterface $sspAssetSearchSortConfigBuilder,
        array $requestParameters
    ): SortConfigTransfer {
        $sortParameter = $sspAssetSearchSortConfigBuilder->getActiveParamName($requestParameters);
        $sortConfigTransfer = $sspAssetSearchSortConfigBuilder->getSortConfigTransfer($sortParameter);

        if ($sortConfigTransfer) {
            return $sortConfigTransfer;
        }

        return $this->config->getDefaultSortConfigTransfer();
    }

    protected function hasViewCompanySspAssetPermission(): bool
    {
        return $this->permissionClient->can('ViewCompanySspAssetPermissionPlugin');
    }

    protected function hasViewBusinessUnitSspAssetPermission(): bool
    {
        return $this->permissionClient->can('ViewBusinessUnitSspAssetPermissionPlugin');
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Form\MerchantRelationRequestListTableFiltersForm;
use Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface;
use Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig;

class MerchantRelationRequestListTableFiltersFormDataProvider implements MerchantRelationRequestListTableFiltersFormDataProviderInterface
{
    /**
     * @uses \Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap::COL_NAME
     *
     * @var string
     */
    protected const COL_COMPANY_NAME = 'spy_company.name';

    /**
     * @var \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface
     */
    protected MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig
     */
    protected MerchantRelationRequestGuiConfig $merchantRelationRequestGuiConfig;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
     * @param \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig $merchantRelationRequestGuiConfig
     */
    public function __construct(
        MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade,
        MerchantRelationRequestGuiConfig $merchantRelationRequestGuiConfig
    ) {
        $this->merchantRelationRequestFacade = $merchantRelationRequestFacade;
        $this->merchantRelationRequestGuiConfig = $merchantRelationRequestGuiConfig;
    }

    /**
     * @return array<string, array<string, int>>
     */
    public function getOptions(): array
    {
        $companyOptions = [];
        $merchantOptions = [];
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->addSort((new SortTransfer())->setField(static::COL_COMPANY_NAME)->setIsAscending(true));
        $readCollectionBatchSize = $this->merchantRelationRequestGuiConfig
            ->getReadMerchantRelationRequestCollectionBatchSize();
        $offset = 0;

        do {
            $paginationTransfer = (new PaginationTransfer())->setOffset($offset)->setLimit($readCollectionBatchSize);
            $merchantRelationRequestCriteriaTransfer->setPagination($paginationTransfer);

            $merchantRelationRequestCollectionTransfer = $this->merchantRelationRequestFacade
                ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

            if (!count($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests())) {
                break;
            }

            [$companyOptions, $merchantOptions] = $this->addOptions(
                $companyOptions,
                $merchantOptions,
                $merchantRelationRequestCollectionTransfer,
            );

            $offset += $readCollectionBatchSize;
        } while (
            count($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()) !== 0
        );

        ksort($merchantOptions);

        return [
            MerchantRelationRequestListTableFiltersForm::OPTION_COMPANIES => $companyOptions,
            MerchantRelationRequestListTableFiltersForm::OPTION_MERCHANTS => $merchantOptions,
        ];
    }

    /**
     * @param array<string, int> $companyOptions
     * @param array<string, int> $merchantOptions
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return list<array<string, int>>
     */
    protected function addOptions(
        array $companyOptions,
        array $merchantOptions,
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): array {
        foreach ($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            $companyTransfer = $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getCompanyOrFail();
            $merchantTransfer = $merchantRelationRequestTransfer->getMerchantOrFail();

            $companyOptions[$companyTransfer->getNameOrFail()] = $companyTransfer->getIdCompanyOrFail();
            $merchantOptions[$merchantTransfer->getNameOrFail()] = $merchantTransfer->getIdMerchantOrFail();
        }

        return [$companyOptions, $merchantOptions];
    }
}

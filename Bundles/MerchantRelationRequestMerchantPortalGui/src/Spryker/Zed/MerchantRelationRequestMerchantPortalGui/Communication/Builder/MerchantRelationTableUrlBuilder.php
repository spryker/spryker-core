<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Builder;

use DateTime;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Service\MerchantRelationRequestMerchantPortalGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig;

class MerchantRelationTableUrlBuilder implements MerchantRelationTableUrlBuilderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig
     */
    protected MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Service\MerchantRelationRequestMerchantPortalGuiToUtilDateTimeServiceInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToUtilDateTimeServiceInterface $dateTimeService;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Service\MerchantRelationRequestMerchantPortalGuiToUtilDateTimeServiceInterface $dateTimeService
     */
    public function __construct(
        MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig,
        MerchantRelationRequestMerchantPortalGuiToUtilDateTimeServiceInterface $dateTimeService
    ) {
        $this->merchantRelationRequestMerchantPortalGuiConfig = $merchantRelationRequestMerchantPortalGuiConfig;
        $this->dateTimeService = $dateTimeService;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return string|null
     */
    public function buildMerchantRelationTableUrl(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): ?string {
        $sortedMerchantRelationshipCreationDates = $this->getSortedMerchantRelationshipCreationDates($merchantRelationRequestTransfer);

        if (!$sortedMerchantRelationshipCreationDates) {
            return null;
        }

        $dateTimeFrom = reset($sortedMerchantRelationshipCreationDates);
        $dateTimeTo = end($sortedMerchantRelationshipCreationDates);
        $dateTimeTo = new DateTime($dateTimeTo);
        $dateTimeTo->modify('+1 second');

        $formattedDateTimeFrom = $this->dateTimeService->formatDateTimeToUtcIso8601(
            $dateTimeFrom,
            $this->merchantRelationRequestMerchantPortalGuiConfig->getDefaultTimezone(),
        );
        $formattedDateTimeTo = $this->dateTimeService->formatDateTimeToUtcIso8601(
            $dateTimeTo,
            $this->merchantRelationRequestMerchantPortalGuiConfig->getDefaultTimezone(),
        );

        return sprintf(
            '%s?%s',
            $this->merchantRelationRequestMerchantPortalGuiConfig->getMerchantRelationTablePath(),
            sprintf(
                $this->merchantRelationRequestMerchantPortalGuiConfig->getMerchantRelationTableQuery(),
                $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnitOrFail()->getFkCompany(),
                $formattedDateTimeFrom,
                $formattedDateTimeTo,
            ),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return list<string>
     */
    protected function getSortedMerchantRelationshipCreationDates(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): array {
        $merchantRelationshipCreationDates = [];

        foreach ($merchantRelationRequestTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $merchantRelationshipCreationDates[] = $merchantRelationshipTransfer->getCreatedAtOrFail();
        }

        usort(
            $merchantRelationshipCreationDates,
            function ($creationDate1, $creationDate2) {
                return strtotime($creationDate1) - strtotime($creationDate2);
            },
        );

        return $merchantRelationshipCreationDates;
    }
}

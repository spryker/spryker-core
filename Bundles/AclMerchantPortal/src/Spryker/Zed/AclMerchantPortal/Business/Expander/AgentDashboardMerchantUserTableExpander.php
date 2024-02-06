<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business\Expander;

use Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer;
use Generated\Shared\Transfer\AclUserHasGroupConditionsTransfer;
use Generated\Shared\Transfer\AclUserHasGroupCriteriaTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;

class AgentDashboardMerchantUserTableExpander implements AgentDashboardMerchantUserTableExpanderInterface
{
    /**
     * @uses \Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantUserGuiTableConfigurationProvider::COL_KEY_ASSIST_USER
     *
     * @var string
     */
    protected const COL_KEY_ASSIST_USER = 'assistUser';

    /**
     * @uses \Spryker\Zed\AgentDashboardMerchantPortalGui\Communication\GuiTable\DataProvider\MerchantUserGuiTableDataProvider::RESPONSE_DATA_KEY_ID_USER
     *
     * @var string
     */
    protected const RESPONSE_DATA_KEY_ID_USER = 'idUser';

    /**
     * @var \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig
     */
    protected AclMerchantPortalConfig $aclMerchantPortalConfig;

    /**
     * @var \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    protected AclMerchantPortalToAclFacadeInterface $aclFacade;

    /**
     * @param \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig $aclMerchantPortalConfig
     * @param \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface $aclFacade
     */
    public function __construct(
        AclMerchantPortalConfig $aclMerchantPortalConfig,
        AclMerchantPortalToAclFacadeInterface $aclFacade
    ) {
        $this->aclMerchantPortalConfig = $aclMerchantPortalConfig;
        $this->aclFacade = $aclFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expand(GuiTableDataResponseTransfer $guiTableDataResponseTransfer): GuiTableDataResponseTransfer
    {
        $userIds = $this->extractUserIdsFromGuiTableDataResponse($guiTableDataResponseTransfer);
        $aclUserHasGroupCollectionTransfer = $this->getAclUserHasGroupCollection($userIds);
        $userIdsIndexedByIdUser = $this->getUserIdsIndexedByIdUserFromAclUserHasGroupCollection(
            $aclUserHasGroupCollectionTransfer,
        );

        foreach ($guiTableDataResponseTransfer->getRows() as $guiTableRowDataResponseTransfer) {
            $responseData = $guiTableRowDataResponseTransfer->getResponseData();
            $idUser = $responseData[static::RESPONSE_DATA_KEY_ID_USER];

            if (isset($userIdsIndexedByIdUser[$idUser])) {
                $responseData[static::COL_KEY_ASSIST_USER] = null;
            }

            $guiTableRowDataResponseTransfer->setResponseData($responseData);
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param list<int> $userIds
     *
     * @return \Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer
     */
    protected function getAclUserHasGroupCollection(
        array $userIds
    ): AclUserHasGroupCollectionTransfer {
        $aclUserHasGroupCriteriaTransfer = (new AclUserHasGroupCriteriaTransfer())->setAclUserHasGroupConditions(
            (new AclUserHasGroupConditionsTransfer())
                ->setUserIds($userIds)
                ->setGroupNames($this->aclMerchantPortalConfig->getBackofficeAllowedAclGroupNames()),
        );

        return $this->aclFacade->getAclUserHasGroupCollection($aclUserHasGroupCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AclUserHasGroupCollectionTransfer $aclUserHasGroupCollectionTransfer
     *
     * @return array<int, int>
     */
    protected function getUserIdsIndexedByIdUserFromAclUserHasGroupCollection(
        AclUserHasGroupCollectionTransfer $aclUserHasGroupCollectionTransfer
    ): array {
        $userIdsIndexedByIdUser = [];

        foreach ($aclUserHasGroupCollectionTransfer->getAclUserHasGroups() as $aclUserHasGroupTransfer) {
            $idUser = $aclUserHasGroupTransfer->getUserOrFail()->getIdUserOrFail();
            $userIdsIndexedByIdUser[$idUser] = $idUser;
        }

        return $userIdsIndexedByIdUser;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return list<int>
     */
    protected function extractUserIdsFromGuiTableDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): array {
        /** @var list<int> $userIds */
        $userIds = array_map(function (GuiTableRowDataResponseTransfer $guiTableRowDataResponseTransfer): string {
            return $guiTableRowDataResponseTransfer->getResponseData()[static::RESPONSE_DATA_KEY_ID_USER];
        }, $guiTableDataResponseTransfer->getRows()->getArrayCopy());

        return $userIds;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\MerchantDashboardActionButtonTransfer;
use Generated\Shared\Transfer\MerchantDashboardCardTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig;
use Twig\Environment;

class MerchantRelationRequestMerchantDashboardCardExpander implements MerchantRelationRequestMerchantDashboardCardExpanderInterface
{
    /**
     * @uses \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Controller\MerchantRelationRequestsController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_RELATION_REQUESTS = '/merchant-relation-request-merchant-portal-gui/merchant-relation-requests?%s';

    /**
     * @var \Twig\Environment
     */
    protected Environment $twigEnvironment;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig
     */
    protected MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Twig\Environment $twigEnvironment
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        Environment $twigEnvironment,
        MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig,
        MerchantRelationRequestMerchantPortalGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade,
        MerchantRelationRequestMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->twigEnvironment = $twigEnvironment;
        $this->merchantRelationRequestMerchantPortalGuiConfig = $merchantRelationRequestMerchantPortalGuiConfig;
        $this->merchantRelationRequestFacade = $merchantRelationRequestFacade;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantDashboardCardTransfer $merchantDashboardCardTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    public function expand(MerchantDashboardCardTransfer $merchantDashboardCardTransfer): MerchantDashboardCardTransfer
    {
        $merchantDashboardCardTransfer = $this->expandContent($merchantDashboardCardTransfer);
        $merchantDashboardCardTransfer = $this->expandActions($merchantDashboardCardTransfer);

        return $merchantDashboardCardTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantDashboardCardTransfer $merchantDashboardCardTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    protected function expandContent(
        MerchantDashboardCardTransfer $merchantDashboardCardTransfer
    ): MerchantDashboardCardTransfer {
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchantOrFail();
        $merchantRelationRequestConditions = (new MerchantRelationRequestConditionsTransfer())
            ->addIdMerchant($idMerchant);

        $totalMerchantRelationCount = $this->merchantRelationRequestFacade->countMerchantRelationRequests(
            (new MerchantRelationRequestCriteriaTransfer())->setMerchantRelationRequestConditions($merchantRelationRequestConditions),
        );
        $pendingMerchantRelationCount = $this->merchantRelationRequestFacade->countMerchantRelationRequests(
            (new MerchantRelationRequestCriteriaTransfer())
                ->setMerchantRelationRequestConditions($merchantRelationRequestConditions->addStatus('pending')),
        );

        $merchantRelationRequestContent = $this->twigEnvironment->render(
            '@MerchantRelationRequestMerchantPortalGui/Partials/merchant_relation_request_merchant_dashboard_card_content.twig',
            [
                'totalMerchantRelationCount' => $totalMerchantRelationCount,
                'pendingMerchantRelationCount' => $pendingMerchantRelationCount,
            ],
        );

        $merchantDashboardCardTransfer->setContent(sprintf(
            '%s%s',
            $merchantRelationRequestContent,
            $merchantDashboardCardTransfer->getContent(),
        ));

        return $merchantDashboardCardTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantDashboardCardTransfer $merchantDashboardCardTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantDashboardCardTransfer
     */
    protected function expandActions(
        MerchantDashboardCardTransfer $merchantDashboardCardTransfer
    ): MerchantDashboardCardTransfer {
        $merchantDashboardCardTransfer->addActionButton(
            (new MerchantDashboardActionButtonTransfer())
                ->setTitle('Manage Pending Requests')
                ->setUrl(sprintf(
                    static::URL_MERCHANT_RELATION_REQUESTS,
                    $this->merchantRelationRequestMerchantPortalGuiConfig->getMerchantRelationRequestTableQuery(),
                )),
        );

        return $merchantDashboardCardTransfer;
    }
}

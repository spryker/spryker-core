<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Expander;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\ProductApprovalGui\Communication\Reader\ProductApprovalStatusReaderInterface;
use Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductFacadeInterface;
use Twig\Environment;

class ProductApprovalProductAbstractEditViewExpander implements ProductApprovalProductAbstractEditViewExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_APPROVAL_STATUS = 'approval-status';

    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Table\ProductTable::COL_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const STATUS_DENIED = 'denied';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DRAFT
     *
     * @var string
     */
    protected const STATUS_DRAFT = 'draft';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @var array<string, string>
     */
    protected const STATUS_TO_BTN_CLASS_MAP = [
        self::STATUS_APPROVED => 'btn-sm safe-submit btn-create',
        self::STATUS_DENIED => 'btn-outline btn-sm safe-submit btn-remove',
        self::STATUS_DRAFT => 'btn-outline btn-sm safe-submit btn-default',
        self::STATUS_WAITING_FOR_APPROVAL => 'btn-outline btn-sm safe-submit btn-view',
    ];

    /**
     * @var string
     */
    protected const BTN_PREFIX = 'approval_status_action.';

    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @uses \Spryker\Zed\ProductApprovalGui\Communication\Controller\EditController::updateApprovalStatusAction()
     *
     * @var string
     */
    protected const URL_UPDATE_APPROVAL_STATUS = '/product-approval-gui/edit/update-approval-status';

    /**
     * @var \Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductApprovalGui\Communication\Reader\ProductApprovalStatusReaderInterface
     */
    protected $productApprovalStatusReader;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @param \Spryker\Zed\ProductApprovalGui\Communication\Reader\ProductApprovalStatusReaderInterface $productApprovalStatusReader
     * @param \Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductFacadeInterface $productFacade
     * @param \Twig\Environment $twig
     */
    public function __construct(
        ProductApprovalStatusReaderInterface $productApprovalStatusReader,
        ProductApprovalGuiToProductFacadeInterface $productFacade,
        Environment $twig
    ) {
        $this->productApprovalStatusReader = $productApprovalStatusReader;
        $this->productFacade = $productFacade;
        $this->twig = $twig;
    }

    /**
     * @param array<mixed> $viewData
     *
     * @return array<mixed>
     */
    public function expand(array $viewData): array
    {
        if (!isset($viewData[static::COL_ID_PRODUCT_ABSTRACT])) {
            return $viewData;
        }

        $productAbstractTransfer = $this->productFacade
            ->findProductAbstractById((int)$viewData[static::COL_ID_PRODUCT_ABSTRACT]);
        if (!$productAbstractTransfer) {
            return $viewData;
        }

        $viewData[ProductAbstractTransfer::APPROVAL_STATUS] = $productAbstractTransfer->getApprovalStatus();
        $applicableApprovalStatuses = $productAbstractTransfer->getApprovalStatus()
            ? $this->productApprovalStatusReader->getApplicableTableActionApprovalStatuses($productAbstractTransfer->getApprovalStatus())
            : [];
        $viewData[static::COL_ACTIONS] = $viewData[static::COL_ACTIONS] ?? [];
        foreach ($applicableApprovalStatuses as $applicableApprovalStatus) {
            $viewData[static::COL_ACTIONS][] = $this->generateButton(
                $applicableApprovalStatus,
                $productAbstractTransfer->getIdProductAbstractOrFail(),
            );
        }

        return $viewData;
    }

    /**
     * @param string $approvalStatus
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function generateButton(string $approvalStatus, int $idProductAbstract): string
    {
        $url = Url::generate(
            static::URL_UPDATE_APPROVAL_STATUS,
            [static::PARAM_APPROVAL_STATUS => $approvalStatus, static::PARAM_ID_PRODUCT_ABSTRACT => $idProductAbstract],
        );

        return $this->twig->render('@ProductApprovalGui/Partials/button.twig', [
            'url' => $url,
            'class' => static::STATUS_TO_BTN_CLASS_MAP[$approvalStatus],
            'title' => sprintf('%s%s', static::BTN_PREFIX, $approvalStatus),
        ]);
    }
}

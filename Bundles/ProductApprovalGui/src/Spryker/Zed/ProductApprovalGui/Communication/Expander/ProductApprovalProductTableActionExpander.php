<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Expander;

use Generated\Shared\Transfer\ButtonCollectionTransfer;
use Generated\Shared\Transfer\ButtonTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductApprovalFacadeInterface;

class ProductApprovalProductTableActionExpander implements ProductApprovalProductTableActionExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Table\ProductTable::COL_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    protected const COL_APPROVAL_STATUS = 'approval_status';

    /**
     * @var string
     */
    protected const PARAM_APPROVAL_STATUS = 'approval-status';

    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @var string
     */
    protected const BTN_PREFIX = 'approval_status_action.';

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
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DRAFT
     *
     * @var string
     */
    protected const STATUS_DRAFT = 'draft';

    /**
     * @var array<string, string>
     */
    protected const STATUS_TO_BTN_CLASS_MAP = [
        self::STATUS_APPROVED => 'btn-create',
        self::STATUS_DENIED => 'btn-remove',
        self::STATUS_DRAFT => 'btn-view',
        self::STATUS_WAITING_FOR_APPROVAL => 'btn-view',
    ];

    /**
     * @uses \Spryker\Zed\ProductApprovalGui\Communication\Controller\EditController::updateApprovalStatusAction()
     *
     * @var string
     */
    protected const URL_UPDATE_APPROVAL_STATUS = '/product-approval-gui/edit/update-approval-status';

    /**
     * @var \Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductApprovalFacadeInterface
     */
    protected $productApprovalFacade;

    /**
     * @param \Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductApprovalFacadeInterface $productApprovalFacade
     */
    public function __construct(ProductApprovalGuiToProductApprovalFacadeInterface $productApprovalFacade)
    {
        $this->productApprovalFacade = $productApprovalFacade;
    }

    /**
     * @param array<mixed> $productData
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function expandWithProductApprovalStatusActions(
        array $productData,
        ButtonCollectionTransfer $buttonCollectionTransfer
    ): ButtonCollectionTransfer {
        if (!isset($productData[static::COL_ID_PRODUCT_ABSTRACT])) {
            return $buttonCollectionTransfer;
        }

        $approvalStatus = $productData[static::COL_APPROVAL_STATUS] ?? ProductApprovalConfig::STATUS_DRAFT;

        $applicableStatuses = $this->productApprovalFacade
            ->getApplicableApprovalStatuses($approvalStatus);

        foreach ($applicableStatuses as $applicableStatus) {
            $defaultOptions = [
                'class' => static::STATUS_TO_BTN_CLASS_MAP[$applicableStatus],
            ];
            $url = Url::generate(
                static::URL_UPDATE_APPROVAL_STATUS,
                [
                    static::PARAM_ID_PRODUCT_ABSTRACT => $productData[static::COL_ID_PRODUCT_ABSTRACT],
                    static::PARAM_APPROVAL_STATUS => $applicableStatus,
                ],
            );

            $buttonTransfer = (new ButtonTransfer())
                ->setUrl($url)
                ->setTitle(sprintf('%s%s', static::BTN_PREFIX, $applicableStatus))
                ->setDefaultOptions($defaultOptions);
            $buttonCollectionTransfer->addButton($buttonTransfer);
        }

        return $buttonCollectionTransfer;
    }
}

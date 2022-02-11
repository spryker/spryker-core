<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Expander;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Twig\Environment;

class ProductApprovalProductTableDataExpander implements ProductApprovalProductTableDataExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Table\ProductTable::COL_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @uses \Spryker\Zed\ProductManagement\Communication\Table\ProductTable::COL_STATUS
     *
     * @var string
     */
    protected const COL_STATUS = 'status';

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
    protected const APPROVAL_STATUS_TO_LABEL_CLASS_MAP = [
        self::STATUS_APPROVED => 'label-info',
        self::STATUS_DENIED => 'label-danger',
        self::STATUS_WAITING_FOR_APPROVAL => 'label-warning',
        self::STATUS_DRAFT => 'label-default',
    ];

    /**
     * @var \Spryker\Zed\ProductApprovalGui\Communication\Expander\ArrayExpanderInterface
     */
    protected $arrayExpander;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @param \Spryker\Zed\ProductApprovalGui\Communication\Expander\ArrayExpanderInterface $arrayExpander
     * @param \Twig\Environment $twig
     */
    public function __construct(ArrayExpanderInterface $arrayExpander, Environment $twig)
    {
        $this->arrayExpander = $arrayExpander;
        $this->twig = $twig;
    }

    /**
     * @param array<array<string, mixed>> $items
     * @param array<array<string, mixed>> $productData
     *
     * @return array<array<string, mixed>>
     */
    public function expandTableData(array $items, array $productData): array
    {
        $productDataIndexedByIdProductAbstract = [];
        foreach ($productData as $productItem) {
            $productDataIndexedByIdProductAbstract[$productItem[static::COL_ID_PRODUCT_ABSTRACT]] = $productItem;
        }

        foreach ($items as $key => $item) {
            $approvalStatus = $productDataIndexedByIdProductAbstract[$item[static::COL_ID_PRODUCT_ABSTRACT]][ProductAbstractTransfer::APPROVAL_STATUS] ?? static::STATUS_DRAFT;
            $approvalStatusLabel = $this->twig->render('@ProductApprovalGui/Partials/label.twig', [
                'title' => $approvalStatus,
                'class' => static::APPROVAL_STATUS_TO_LABEL_CLASS_MAP[$approvalStatus],
            ]);

            $items[$key] = $this->arrayExpander->insertArrayItemAfterKey(
                $item,
                static::COL_STATUS,
                [
                    ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatusLabel,
                ],
            );
        }

        return $items;
    }
}

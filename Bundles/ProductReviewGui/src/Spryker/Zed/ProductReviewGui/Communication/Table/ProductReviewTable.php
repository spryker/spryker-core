<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\ProductReview\Persistence\SpyProductReview;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilDateTimeInterface;
use Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilSanitizeInterface;
use Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface;

class ProductReviewTable extends AbstractTable
{
    /**
     * @var \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface
     */
    protected $productReviewGuiQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilDateTimeInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilSanitizeInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface $productReviewGuiQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilDateTimeInterface $utilDateTimeService
     * @param \Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilSanitizeInterface $utilSanitizeService
     */
    public function __construct(
        ProductReviewGuiQueryContainerInterface $productReviewGuiQueryContainer,
        LocaleTransfer $localeTransfer,
        ProductReviewGuiToUtilDateTimeInterface $utilDateTimeService,
        ProductReviewGuiToUtilSanitizeInterface $utilSanitizeService
    ) {
        $this->productReviewGuiQueryContainer = $productReviewGuiQueryContainer;
        $this->localeTransfer = $localeTransfer;
        $this->utilDateTimeService = $utilDateTimeService;
        $this->utilSanitizeService = $utilSanitizeService;

        $this->localeTransfer->requireIdLocale();
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableIdentifier(ProductReviewTableConstants::TABLE_IDENTIFIER);

        $config->setHeader([
            ProductReviewTableConstants::COL_SHOW_DETAILS => '',
            ProductReviewTableConstants::COL_ID_PRODUCT_REVIEW => 'ID',
            ProductReviewTableConstants::COL_CREATED => 'Date',
            ProductReviewTableConstants::COL_CUSTOMER_NAME => 'Customer',
            ProductReviewTableConstants::COL_NICK_NAME => 'Nickname',
            ProductReviewTableConstants::COL_PRODUCT_NAME => 'Product Name',
            ProductReviewTableConstants::COL_RATING => 'Rating',
            ProductReviewTableConstants::COL_STATUS => 'Status',
            ProductReviewTableConstants::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            ProductReviewTableConstants::COL_SHOW_DETAILS,
            ProductReviewTableConstants::COL_STATUS,
            ProductReviewTableConstants::COL_ACTIONS,
            ProductReviewTableConstants::COL_CUSTOMER_NAME,
            ProductReviewTableConstants::COL_PRODUCT_NAME,
            ProductReviewTableConstants::EXTRA_DETAILS,
        ]);

        $config->setSearchable([
            ProductReviewTableConstants::COL_NICK_NAME,
            ProductReviewTableConstants::COL_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES_NAME,
            ProductReviewTableConstants::COL_CUSTOMER_FIRST_NAME,
            ProductReviewTableConstants::COL_CUSTOMER_LAST_NAME,
        ]);

        $config->setSortable([
            ProductReviewTableConstants::COL_ID_PRODUCT_REVIEW,
            ProductReviewTableConstants::COL_CREATED,
            ProductReviewTableConstants::COL_NICK_NAME,
            ProductReviewTableConstants::COL_PRODUCT_NAME,
            ProductReviewTableConstants::COL_RATING,
            ProductReviewTableConstants::COL_STATUS,
        ]);

        $config->setExtraColumns([
            ProductReviewTableConstants::EXTRA_DETAILS,
        ]);

        $config->setDefaultSortField(ProductReviewTableConstants::COL_ID_PRODUCT_REVIEW, ProductReviewTableConstants::SORT_DESC);
        $config->setStateSave(false);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productReviewGuiQueryContainer->queryProductReview($this->localeTransfer->getIdLocale());

        $productReviewCollection = $this->runQuery($query, $config, true);

        $tableData = [];
        foreach ($productReviewCollection as $productReviewEntity) {
            $tableData[] = $this->generateItem($productReviewEntity);
        }

        return $tableData;
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return array
     */
    protected function generateItem(SpyProductReview $productReviewEntity)
    {
        return [
            ProductReviewTableConstants::COL_ID_PRODUCT_REVIEW => $productReviewEntity->getIdProductReview(),
            ProductReviewTableConstants::COL_CREATED => $this->getCreatedAt($productReviewEntity),
            ProductReviewTableConstants::COL_CUSTOMER_NAME => $this->getCustomerName($productReviewEntity),
            ProductReviewTableConstants::COL_NICK_NAME => $productReviewEntity->getNickname(),
            ProductReviewTableConstants::COL_PRODUCT_NAME => $this->getProductName($productReviewEntity),
            ProductReviewTableConstants::COL_RATING => $productReviewEntity->getRating(),
            ProductReviewTableConstants::COL_STATUS => $this->getStatusLabel($productReviewEntity->getStatus()),
            ProductReviewTableConstants::COL_ACTIONS => $this->createActionButtons($productReviewEntity),
            ProductReviewTableConstants::COL_SHOW_DETAILS => $this->createShowDetailsButton(),
            ProductReviewTableConstants::EXTRA_DETAILS => $this->generateDetails($productReviewEntity),
        ];
    }

    /**
     * @param string $status
     *
     * @return string
     */
    protected function getStatusLabel($status)
    {
        switch ($status) {
            case ProductReviewTableConstants::COL_PRODUCT_REVIEW_STATUS_REJECTED:
                return $this->generateLabel('Rejected', 'label-danger');
            case ProductReviewTableConstants::COL_PRODUCT_REVIEW_STATUS_APPROVED:
                return $this->generateLabel('Approved', 'label-info');
            case ProductReviewTableConstants::COL_PRODUCT_REVIEW_STATUS_PENDING:
            default:
                return $this->generateLabel('Pending', 'label-secondary');
        }
    }

    /**
     * @return string
     */
    protected function createShowDetailsButton()
    {
        return '<i class="fa fa-chevron-down"></i>';
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return string
     */
    protected function createActionButtons(SpyProductReview $productReviewEntity)
    {
        $actions = [];

        $actions[] = $this->generateStatusChangeButton($productReviewEntity);
        $actions[] = $this->generateRemoveButton(
            Url::generate('/product-review-gui/delete', [
                ProductReviewTableConstants::PARAM_ID => $productReviewEntity->getIdProductReview(),
            ]),
            'Delete'
        );

        return implode(' ', $actions);
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return string
     */
    protected function generateStatusChangeButton(SpyProductReview $productReviewEntity)
    {
        $buttonGroupItems = [];
        switch ($productReviewEntity->getStatus()) {
            case ProductReviewTableConstants::COL_PRODUCT_REVIEW_STATUS_REJECTED:
                $buttonGroupItems[] = $this->generateApproveButtonGroupItem($productReviewEntity);
                break;
            case ProductReviewTableConstants::COL_PRODUCT_REVIEW_STATUS_APPROVED:
                $buttonGroupItems[] = $this->generateRejectButtonGroupItem($productReviewEntity);
                break;
            case ProductReviewTableConstants::COL_PRODUCT_REVIEW_STATUS_PENDING:
            default:
                $buttonGroupItems[] = $this->generateApproveButtonGroupItem($productReviewEntity);
                $buttonGroupItems[] = $this->generateRejectButtonGroupItem($productReviewEntity);
                break;
        }

        return $this->generateButtonGroup(
            $buttonGroupItems,
            'Change status ',
            [
                'icon' => '',
            ]
        );
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return array
     */
    protected function generateApproveButtonGroupItem(SpyProductReview $productReviewEntity)
    {
        return $this->createButtonGroupItem(
            'Approve',
            Url::generate('/product-review-gui/update/approve', [
                ProductReviewTableConstants::PARAM_ID => $productReviewEntity->getIdProductReview(),
            ])
        );
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return array
     */
    protected function generateRejectButtonGroupItem(SpyProductReview $productReviewEntity)
    {
        return $this->createButtonGroupItem(
            'Reject',
            Url::generate('/product-review-gui/update/reject', [
                ProductReviewTableConstants::PARAM_ID => $productReviewEntity->getIdProductReview(),
            ])
        );
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return string
     */
    protected function generateDetails(SpyProductReview $productReviewEntity)
    {
        return sprintf(
            '<table class="details">
                <tr>
                    <th>Summary</th>
                    <td>%s</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>%s</td>
                </tr>
            </table>',
            $this->utilSanitizeService->escapeHtml($productReviewEntity->getSummary()),
            $this->utilSanitizeService->escapeHtml($productReviewEntity->getDescription())
        );
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return string
     */
    protected function getCustomerName(SpyProductReview $productReviewEntity)
    {
        return sprintf(
            '<a href="%s" target="_blank">%s %s</a>',
            Url::generate('/customer/view', [
                'id-customer' => $productReviewEntity->getVirtualColumn(ProductReviewTableConstants::COL_PRODUCT_REVIEW_GUI_ID_CUSTOMER),
            ]),
            $productReviewEntity->getVirtualColumn(ProductReviewTableConstants::COL_PRODUCT_REVIEW_GUI_FIRST_NAME),
            $productReviewEntity->getVirtualColumn(ProductReviewTableConstants::COL_PRODUCT_REVIEW_GUI_LAST_NAME)
        );
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return mixed
     */
    protected function getProductName(SpyProductReview $productReviewEntity)
    {
        return sprintf(
            '<a href="%s" target="_blank">%s</a>',
            Url::generate('/product-management/view', [
                'id-product-abstract' => $productReviewEntity->getFkProductAbstract(),
            ]),
            $productReviewEntity->getVirtualColumn(ProductReviewTableConstants::COL_PRODUCT_NAME)
        );
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return string|\DateTime
     */
    protected function getCreatedAt(SpyProductReview $productReviewEntity)
    {
        return $this->utilDateTimeService->formatDateTime($productReviewEntity->getCreatedAt());
    }
}

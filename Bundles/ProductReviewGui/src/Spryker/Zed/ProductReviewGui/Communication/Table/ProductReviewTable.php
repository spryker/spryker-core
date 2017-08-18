<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReview\Persistence\SpyProductReview;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductReviewGui\Communication\Controller\EditController;
use Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilDateTimeInterface;
use Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilSanitizeInterface;
use Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainer;
use Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface;

// TODO: decrease dependencies
class ProductReviewTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-review-table';

    const COL_ID_PRODUCT_REVIEW = 'id_product_review';
    const COL_CREATED = ProductReviewGuiQueryContainer::FIELD_CREATED;
    const COL_CUSTOMER_NAME = 'customer_name';
    const COL_NICK_NAME = 'nickname';
    const COL_PRODUCT_NAME = ProductReviewGuiQueryContainer::FIELD_PRODUCT_NAME;
    const COL_RATING = 'rating';
    const COL_STATUS = 'status';
    const COL_ACTIONS = 'actions';
    const COL_SHOW_DETAILS = 'show_details';
    const EXTRA_DETAILS = 'details';

    /**
     * @var \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface
     */
    protected $productReviewGuiQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
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
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $config->setHeader([
            static::COL_SHOW_DETAILS => '',
            static::COL_ID_PRODUCT_REVIEW => 'ID',
            static::COL_CREATED => 'Date',
            static::COL_CUSTOMER_NAME => 'Customer',
            static::COL_NICK_NAME => 'Nickname',
            static::COL_PRODUCT_NAME => 'Product Name',
            static::COL_RATING => 'Rating',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_SHOW_DETAILS,
            static::COL_STATUS,
            static::COL_ACTIONS,
            static::COL_CUSTOMER_NAME,
            static::COL_PRODUCT_NAME,
        ]);

        $config->setSearchable([
            static::COL_NICK_NAME,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            SpyCustomerTableMap::COL_FIRST_NAME,
            SpyCustomerTableMap::COL_LAST_NAME,
        ]);

        $config->setSortable([
            static::COL_ID_PRODUCT_REVIEW,
            static::COL_CREATED,
            static::COL_NICK_NAME,
            static::COL_PRODUCT_NAME,
            static::COL_RATING,
            static::COL_STATUS,
        ]);

        $config->setDefaultSortField(static::COL_ID_PRODUCT_REVIEW, TableConfiguration::SORT_DESC);
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
            static::COL_ID_PRODUCT_REVIEW => $productReviewEntity->getIdProductReview(),
            static::COL_CREATED => $this->getCreatedAt($productReviewEntity),
            static::COL_CUSTOMER_NAME => $this->getCustomerName($productReviewEntity),
            static::COL_NICK_NAME => $productReviewEntity->getNickname(),
            static::COL_PRODUCT_NAME => $this->getProductName($productReviewEntity),
            static::COL_RATING => $productReviewEntity->getRating(),
            static::COL_STATUS => $this->getStatusLabel($productReviewEntity->getStatus()),
            static::COL_ACTIONS => $this->createActionButtons($productReviewEntity),
            static::COL_SHOW_DETAILS => $this->createShowDetailsButton(),
            static::EXTRA_DETAILS => $this->generateDetails($productReviewEntity),
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
            case SpyProductReviewTableMap::COL_STATUS_REJECTED:
                $label = '<span class="label label-danger">Rejected</span>';
                break;
            case SpyProductReviewTableMap::COL_STATUS_APPROVED:
                $label = '<span class="label label-info">Approved</span>';
                break;
            case SpyProductReviewTableMap::COL_STATUS_PENDING:
            default:
                $label = '<span class="label label-secondary">Pending</span>';
                break;
        }

        return $label;
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
                EditController::PARAM_ID => $productReviewEntity->getIdProductReview(),
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
            case SpyProductReviewTableMap::COL_STATUS_REJECTED:
                $buttonGroupItems[] = $this->generateApproveButtonGroupItem($productReviewEntity);
                break;
            case SpyProductReviewTableMap::COL_STATUS_APPROVED:
                $buttonGroupItems[] = $this->generateRejectButtonGroupItem($productReviewEntity);
                break;
            case SpyProductReviewTableMap::COL_STATUS_PENDING:
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
     * @return string
     */
    protected function generateApproveButtonGroupItem(SpyProductReview $productReviewEntity)
    {
        return $this->createButtonGroupItem(
            'Approve',
            Url::generate('/product-review-gui/edit/approve', [
                EditController::PARAM_ID => $productReviewEntity->getIdProductReview(),
            ])
        );
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return string
     */
    protected function generateRejectButtonGroupItem(SpyProductReview $productReviewEntity)
    {
        return $this->createButtonGroupItem(
            'Reject',
            Url::generate('/product-review-gui/edit/reject', [
                EditController::PARAM_ID => $productReviewEntity->getIdProductReview(),
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
                'id-customer' => $productReviewEntity->getVirtualColumn(ProductReviewGuiQueryContainer::FIELD_ID_CUSTOMER),
            ]),
            $productReviewEntity->getVirtualColumn(ProductReviewGuiQueryContainer::FIELD_CUSTOMER_FIRST_NAME),
            $productReviewEntity->getVirtualColumn(ProductReviewGuiQueryContainer::FIELD_CUSTOMER_LAST_NAME)
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
            $productReviewEntity->getVirtualColumn(static::COL_PRODUCT_NAME)
        );
    }

    /**
     * @param \Orm\Zed\ProductReview\Persistence\SpyProductReview $productReviewEntity
     *
     * @return string
     */
    protected function getCreatedAt(SpyProductReview $productReviewEntity)
    {
        return $this->utilDateTimeService->formatDateTime($productReviewEntity->getCreatedAt());
    }

}

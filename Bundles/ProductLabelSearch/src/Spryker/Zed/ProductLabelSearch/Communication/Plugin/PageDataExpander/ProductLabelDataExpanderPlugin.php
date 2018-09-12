<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Communication\Plugin\PageDataExpander;

use DateTime;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Communication\ProductLabelSearchCommunicationFactory getFactory()
 */
class ProductLabelDataExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $allLabelIds = $this->getFactory()->getProductLabelFacade()->findLabelIdsByIdProductAbstract($productData['fk_product_abstract']);
        $labelEntities = SpyProductLabelQuery::create()
            ->filterByIdProductLabel_In($allLabelIds)
            ->filterByIsActive(true)
            ->find();

        $activeLabelIds = [];
        foreach ($labelEntities as $labelEntity) {
            if ($this->isValidByDate($labelEntity)) {
                $activeLabelIds[] = $labelEntity->getIdProductLabel();
            }
        }

        $productAbstractPageSearchTransfer->setLabelIds($activeLabelIds);
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $spyProductLabel
     *
     * @return bool
     */
    protected function isValidByDate(SpyProductLabel $spyProductLabel)
    {
        $isValidFromDate = $this->isValidByDateFrom($spyProductLabel);
        $isValidToDate = $this->isValidByDateTo($spyProductLabel);

        return ($isValidFromDate && $isValidToDate);
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabel
     *
     * @return bool
     */
    protected function isValidByDateFrom(SpyProductLabel $productLabel)
    {
        if (!$productLabel->getValidFrom()) {
            return true;
        }

        $now = new DateTime();

        if ($now < $productLabel->getValidFrom()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabel
     *
     * @return bool
     */
    protected function isValidByDateTo(SpyProductLabel $productLabel)
    {
        if (!$productLabel->getValidTo()) {
            return true;
        }

        $now = new DateTime();

        if ($productLabel->getValidTo() < $now) {
            return false;
        }

        return true;
    }
}

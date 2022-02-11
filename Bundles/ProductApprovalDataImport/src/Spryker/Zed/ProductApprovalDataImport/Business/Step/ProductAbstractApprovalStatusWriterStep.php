<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductApprovalDataImport\Business\Step;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductApprovalDataImport\Business\DataSet\ProductApprovalStatusDataSetInterface;

class ProductAbstractApprovalStatusWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Zed\Product\Dependency\ProductEvents::PRODUCT_ABSTRACT_PUBLISH
     *
     * @var string
     */
    protected const PRODUCT_ABSTRACT_PUBLISH = 'Product.product_abstract.publish';

    /**
     * @var string
     */
    protected const APPROVAL_STATUS = ProductApprovalStatusDataSetInterface::APPROVAL_STATUS;

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_SKU = ProductApprovalStatusDataSetInterface::PRODUCT_ABSTRACT_SKU;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productAbstractSku = $dataSet[static::PRODUCT_ABSTRACT_SKU];
        $approvalStatus = $dataSet[static::APPROVAL_STATUS];

        if (!$productAbstractSku) {
            throw new InvalidDataException(sprintf('"%s" is required.', static::PRODUCT_ABSTRACT_SKU));
        }

        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku($productAbstractSku)
            ->findOne();

        if (!$productAbstractEntity) {
            throw new EntityNotFoundException(sprintf('Could not find Product Abstract by SKU "%s".', $productAbstractSku));
        }

        $productAbstractEntity->setApprovalStatus($approvalStatus);
        $productAbstractEntity->save();

        $this->addPublishEvents(static::PRODUCT_ABSTRACT_PUBLISH, $productAbstractEntity->getIdProductAbstract());
    }
}

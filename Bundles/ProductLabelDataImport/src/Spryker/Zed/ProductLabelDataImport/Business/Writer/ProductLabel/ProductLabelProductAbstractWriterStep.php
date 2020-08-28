<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet\ProductLabelDataSetInterface;

class ProductLabelProductAbstractWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig::PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH
     */
    protected const EVENT_PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH = 'ProductLabel.product_abstract_label.publish';

    /**
     * @uses \Spryker\Zed\Product\Dependency\ProductEvents::PRODUCT_ABSTRACT_PUBLISH
     */
    protected const EVENT_PRODUCT_ABSTRACT_PUBLISH = 'Product.product_abstract.publish';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[ProductLabelDataSetInterface::COL_PRODUCT_ABSTRACT_IDS])) {
            return;
        }

        foreach ($dataSet[ProductLabelDataSetInterface::COL_PRODUCT_ABSTRACT_IDS] as $idProductAbstract) {
            $productLabelAbstractProductEntity = SpyProductLabelProductAbstractQuery::create()
                ->filterByFkProductLabel($dataSet[ProductLabelDataSetInterface::COL_ID_PRODUCT_LABEL])
                ->filterByFkProductAbstract($idProductAbstract)
                ->findOneOrCreate();

            if ($productLabelAbstractProductEntity->isNew() || $productLabelAbstractProductEntity->isModified()) {
                $productLabelAbstractProductEntity->save();

                $this->addPublishEvents(static::EVENT_PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
                $this->addPublishEvents(static::EVENT_PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
            }
        }
    }
}

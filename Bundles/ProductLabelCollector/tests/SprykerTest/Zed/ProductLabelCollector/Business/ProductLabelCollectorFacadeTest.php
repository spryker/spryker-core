<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\ProductLabelCollector\Business;

use Codeception\Test\Unit;
use DateTime;
use Spryker\Shared\ProductLabel\ProductLabelConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group ProductLabelCollector
 * @group Business
 * @group Facade
 * @group ProductLabelCollectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductLabelCollectorFacadeTest extends Unit
{
    public const METHOD_FOR_RELATION_COLLECTION = 'runProductAbstractRelationStorageCollector';

    /**
     * @var \SprykerTest\Zed\ProductLabelCollector\ProductLabelCollectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCollectRelationShouldWhenDeactivatedShouldRemoveInactiveRelations()
    {
        $productTransfer = $this->tester->haveProduct();
        $idProductAbstract = $productTransfer->getFkProductAbstract();
        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        $productLabelCollectorFacade = $this->getProductLabelCollectorFacade();

        $data = $this->tester->runCollector(
            $productLabelCollectorFacade,
            static::METHOD_FOR_RELATION_COLLECTION,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            new DateTime('-10 Seconds')
        );

        $storageKey = key($data[0]);
        $this->assertCount(1, $data[0][$storageKey]);

        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelTransfer = $productLabelFacade->findLabelById($idProductLabel);
        $productLabelTransfer->setIsActive(false);
        $productLabelFacade->updateLabel($productLabelTransfer);

        $data = $this->tester->runCollector(
            $productLabelCollectorFacade,
            static::METHOD_FOR_RELATION_COLLECTION,
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            new DateTime('-10 Second')
        );

        $this->assertCount(0, $data[0][$storageKey]);
    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    public function getProductLabelFacade()
    {
        return $this->tester->getLocator()->productLabel()->facade();
    }

    /**
     * @return \Spryker\Zed\ProductLabelCollector\Business\ProductLabelCollectorFacadeInterface
     */
    public function getProductLabelCollectorFacade()
    {
        return $this->tester->getLocator()->productLabelCollector()->facade();
    }
}

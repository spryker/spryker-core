<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\ProductLabelCollector\Business;

use Codeception\Test\Unit;
use DateTime;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResult;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacade;
use Spryker\Zed\ProductLabel\ProductLabelConfig;
use Spryker\Zed\ProductLabelCollector\Business\ProductLabelCollectorFacade;
use Spryker\Zed\ProductLabelCollector\Persistence\Collector\Propel\ProductAbstractRelationCollectorQuery;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelCollector
 * @group Business
 * @group Facade
 * @group ProductLabelCollectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductLabelCollectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductLabelCollector\ProductLabelCollectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCollectRelationShouldWhenDeactivatedShouldRemoveInactiveRelations()
    {
        $productLabelFacade = $this->getProductLabelFacade();
        $productLabelTransfer = $productLabelFacade->findLabelById(1);
        $productLabelTransfer->setIsActive(false);
        $productLabelFacade->updateLabel($productLabelTransfer);

        $localeTransfer = (new LocaleFacade())->getCurrentLocale();

        $touchQueryContainer = new TouchQueryContainer();
        $baseQuery = $touchQueryContainer->createBasicExportableQuery(
            ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS,
            $localeTransfer,
            new DateTime('Yesterday')
        )->withColumn(SpyTouchTableMap::COL_ID_TOUCH, CollectorConfig::COLLECTOR_TOUCH_ID)
         ->withColumn(SpyTouchTableMap::COL_ITEM_ID, CollectorConfig::COLLECTOR_RESOURCE_ID)
         ->setFormatter(new PropelArraySetFormatter());

        $dataReader = $this->getMockBuilder(ReaderInterface::class)->getMock();
        $writerReader = $this->getMockBuilder(WriterInterface::class)->getMock();
        $touchUpdater = $this->getMockBuilder(TouchUpdaterInterface::class)->getMock();

        $collectedData = [];
        $writerReader->method('write')->with(
            $this->callback(function($data) use(&$collectedData) {
                $collectedData[] = $data;
                return $data;
            }
        ));

        $productLabelCollectorFacade = new ProductLabelCollectorFacade();
        $productLabelCollectorFacade->runProductAbstractRelationStorageCollector(
            $baseQuery,
            $localeTransfer,
            new BatchResult(),
            $dataReader,
            $writerReader,
            $touchUpdater,
            new NullOutput()
        );


    }

    /**
     * @return \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    public function getProductLabelFacade()
    {
        return $this->tester->getLocator()->productLabel()->facade();
    }
}

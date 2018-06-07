<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataSet;

use Codeception\Test\Unit;
use SprykerTest\Zed\DataImport\_support\Helper\DataImportStepBeforeAndAfter;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataSet
 * @group DataSetStepBrokerTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class DataSetStepBrokerTest extends Unit
{
    /**
     * @return void
     */
    public function testExecutesDataImportSteps()
    {
        $dataSetStepBroker = $this->tester->getFactory()->createDataSetStepBroker();
        $dataSetStepBroker->addStep($this->tester->getDataImportStepMock());

        $dataSetStepBroker->execute($this->tester->getFactory()->createDataSet());
    }

    /**
     * @return void
     */
    public function testExecutesBeforeAndAfterDataImportSteps()
    {
        $dataSetStepBroker = $this->tester->getFactory()->createDataSetStepBroker();
        $dataSetStepBroker->addStep(new DataImportStepBeforeAndAfter());

        $dataSetStepBroker->execute($this->tester->getFactory()->createDataSet());
    }
}

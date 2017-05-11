<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataSet;

use Codeception\TestCase\Test;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetImporter;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataSet
 * @group DataSetImporterTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class DataSetImporterTest extends Test
{

    /**
     * @return void
     */
    public function testExecutesDataImportSteps()
    {

        $dataSetImporter = $this->tester->getFactory()->createDataSetImporter();
        $dataSetImporter->addDataImportStep($this->tester->getDataImportStepMock());

        $dataSetImporter->execute($this->tester->getFactory()->createDataSet());
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport\Business\Model\DataImportStep;

use Codeception\Test\Unit;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group DataImportStep
 * @group ReNameDataSetKeysStepTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\DataImport\BusinessTester $tester
 */
class ReNameDataSetKeysStepTest extends Unit
{
    public const ORIGINAL_KEY_A = 'key-a';
    public const NEW_KEY_A = 'new-key-a';
    public const VALUE_A = 'value a';

    /**
     * @return void
     */
    public function testExecuteReNamesKeysInDataSet()
    {
        $dataSet = $this->tester->getFactory()->createDataSet([
            static::ORIGINAL_KEY_A => static::VALUE_A,
        ]);

        $keyMapStep = $this->tester->getFactory()->createRenameDataSetKeysStep([
            static::ORIGINAL_KEY_A => static::NEW_KEY_A,
        ]);

        $keyMapStep->execute($dataSet);

        $this->assertKeyIsReNamed(static::NEW_KEY_A, static::ORIGINAL_KEY_A, static::VALUE_A, $dataSet);
    }

    /**
     * @param string $newKey
     * @param string $oldKey
     * @param string $value
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSet $dataSet
     *
     * @return void
     */
    protected function assertKeyIsReNamed($newKey, $oldKey, $value, DataSet $dataSet)
    {
        $this->assertArrayNotHasKey($oldKey, $dataSet, sprintf('Expected that "%s" is no longer present in data set but was found in data set.', $oldKey));
        $this->assertArrayHasKey($newKey, $dataSet, sprintf(
            'Expected that key "%s" is re-named to "%s" but new key name was not found in data set.',
            $oldKey,
            $newKey
        ));
        $this->assertSame($value, $dataSet[$newKey], 'Expected that original value is copied to new key but it was not copied');
    }
}

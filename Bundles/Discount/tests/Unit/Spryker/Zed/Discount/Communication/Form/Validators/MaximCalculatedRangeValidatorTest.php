<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Communication\Form\Validators;

use Codeception\TestCase\Test;
use Spryker\Zed\Discount\Communication\Form\Validators\MaximumCalculatedRangeValidator;

class MaximCalculatedRangeValidatorTest extends Test
{

    /**
     * @dataProvider getPairs
     *
     * @param int $codeLength
     * @param int $charactersAllowedNumber
     * @param int $numberOfPossibilities
     *
     * @return void
     */
    public function testResults($codeLength, $charactersAllowedNumber, $numberOfPossibilities)
    {
        $maximRangeCalculator = new MaximumCalculatedRangeValidator($charactersAllowedNumber);

        $this->assertEquals($numberOfPossibilities, $maximRangeCalculator->getPossibleCodeCombinationsCount($codeLength));
    }

    /**
     * @return array
     */
    public function getPairs()
    {
        return [
            [0, -10, 0],
            [-1, 1, 0],
            [0, 0, 0],
            [6, 3, 0],
            [3, 3, 1],
            [2, 3, 3],
            [2, 6, 15],
            [1, 6, 6],
            [3, 32, 4960],
        ];
    }

}

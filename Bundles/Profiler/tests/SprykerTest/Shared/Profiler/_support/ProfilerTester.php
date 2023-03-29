<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Profiler;

use Codeception\Actor;
use Generated\Shared\Transfer\ProfilerDataTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProfilerTester extends Actor
{
    use _generated\ProfilerTesterActions;

    /**
     * @return array<string, array<string, int>>
     */
    public function haveXhprofProfilerCallTrace(): array
    {
        return [
            'main()' => ['ct' => 50000, 'wt' => 50000],
            'main()==>Spryker\Zed\ModuleA\Business\FacadeA::a' => ['ct' => 40000, 'wt' => 40000],
            'main()==>Spryker\Zed\ModuleB\Business\FacadeB::b' => ['ct' => 40000, 'wt' => 40000],
            'Spryker\Zed\ModuleB\Business\FacadeB::b==>Spryker\Zed\ModuleC\Business\FacadeC::c' => ['ct' => 30000, 'wt' => 30000],
            'Spryker\Zed\ModuleC\Business\FacadeC::c==>Spryker\Zed\ModuleD\Business\FacadeD::d' => ['ct' => 30000, 'wt' => 30000],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProfilerDataTransfer $profilerDataTransfer
     *
     * @return void
     */
    public function assertGeneratedProfilerDataValid(ProfilerDataTransfer $profilerDataTransfer): void
    {
        $dataContent = $profilerDataTransfer->getContent();
        $dataStats = $profilerDataTransfer->getStats();
        $dataType = $profilerDataTransfer->getType();

        $this->assertRegExp('/<svg.*>.*<\\/svg>/s', $dataContent);
        $this->assertStringContainsString('Spryker\Zed\ModuleA\Business\FacadeA::a', $dataContent);
        $this->assertStringContainsString('Spryker\Zed\ModuleB\Business\FacadeB::b', $dataContent);
        $this->assertStringContainsString('Spryker\Zed\ModuleC\Business\FacadeC::c', $dataContent);
        $this->assertStringContainsString('Spryker\Zed\ModuleD\Business\FacadeD::d', $dataContent);

        $this->assertSame(['Calls' => 5], $dataStats);

        $this->assertSame('svg', $dataType);
    }

    /**
     * @param \Generated\Shared\Transfer\ProfilerDataTransfer $profilerDataTransfer
     *
     * @return void
     */
    public function assertGeneratedProfilerDataHasOnlySlowerNodes(ProfilerDataTransfer $profilerDataTransfer): void
    {
        $dataContent = $profilerDataTransfer->getContent();
        $dataStats = $profilerDataTransfer->getStats();
        $dataType = $profilerDataTransfer->getType();

        $this->assertRegExp('/<svg.*>.*<\\/svg>/s', $dataContent);
        $this->assertStringContainsString('Spryker\Zed\ModuleA\Business\FacadeA::a', $dataContent);
        $this->assertStringContainsString('Spryker\Zed\ModuleB\Business\FacadeB::b', $dataContent);
        $this->assertStringNotContainsString('Spryker\Zed\ModuleC\Business\FacadeC::c', $dataContent);
        $this->assertStringNotContainsString('Spryker\Zed\ModuleD\Business\FacadeD::d', $dataContent);

        $this->assertSame(['Calls' => 3], $dataStats);

        $this->assertSame('svg', $dataType);
    }
}

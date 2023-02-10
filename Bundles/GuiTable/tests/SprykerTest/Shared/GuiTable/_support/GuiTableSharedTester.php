<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\GuiTable;

use Codeception\Actor;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilder;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceBridge;
use Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface;
use Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatter;
use Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatterInterface;
use Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizer;
use Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizerInterface;

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
class GuiTableSharedTester extends Actor
{
    use _generated\GuiTableSharedTesterActions;

    /**
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    public function createGuiTableConfigurationBuilder(): GuiTableConfigurationBuilderInterface
    {
        return new GuiTableConfigurationBuilder();
    }

    /**
     * @return \Spryker\Shared\GuiTable\Normalizer\DateRangeRequestFilterValueNormalizerInterface
     */
    public function createDateRangeRequestFilterValueNormalizer(): DateRangeRequestFilterValueNormalizerInterface
    {
        return new DateRangeRequestFilterValueNormalizer();
    }

    /**
     * @return \Spryker\Shared\GuiTable\Http\DataResponse\DataResponseFormatterInterface
     */
    public function createDataResponseFormatter(): DataResponseFormatterInterface
    {
        return new DataResponseFormatter($this->createGuiTableToUtilDateTimeServiceBridge());
    }

    /**
     * @return \Spryker\Shared\GuiTable\Dependency\Service\GuiTableToUtilDateTimeServiceInterface
     */
    protected function createGuiTableToUtilDateTimeServiceBridge(): GuiTableToUtilDateTimeServiceInterface
    {
        return new GuiTableToUtilDateTimeServiceBridge($this->getLocator()->utilDateTime()->service());
    }
}

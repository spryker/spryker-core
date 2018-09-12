<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Plugin;

use Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface;
use Spryker\Zed\EventBehavior\EventBehaviorConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 * @method \Spryker\Zed\DataImport\Communication\DataImportCommunicationFactory getFactory()
 */
class DataImportEventBehaviorPlugin extends AbstractPlugin implements DataImportBeforeImportHookInterface, DataImportAfterImportHookInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function beforeImport()
    {
        EventBehaviorConfig::disableEvent();
    }

    /**
     * @api
     *
     * @return void
     */
    public function afterImport()
    {
        EventBehaviorConfig::enableEvent();
    }
}

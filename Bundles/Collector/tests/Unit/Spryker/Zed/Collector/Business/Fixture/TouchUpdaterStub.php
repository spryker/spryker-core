<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Collector\Business\Fixture;

use Spryker\Zed\Collector\Business\Exporter\Writer\AbstractTouchUpdater;

class TouchUpdaterStub extends AbstractTouchUpdater
{

    /**
     * @var string
     */
    protected $touchKeyTableName = 'touchKeyTableName_value';

    /**
     * @var string
     */
    protected $touchKeyIdColumnName = 'touchKeyIdColumnName_value';

    /**
     * @var string
     */
    protected $touchKeyColumnName = 'touchKeyColumnName_value';

    /**
     * @return null
     */
    protected function createTouchKeyEntity()
    {
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Collector\Business\Fixture;

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
     * @param string $key
     * @param int $idLocale
     * @param int $idStore
     *
     * @return null
     */
    protected function findOrCreateTouchKeyEntity($key, $idLocale, $idStore)
    {
    }

    /**
     * @param string[] $keys
     * @param int $idLocale
     *
     * @return void
     */
    public function deleteTouchKeyEntities($keys, $idLocale): void
    {
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Application\Plugin\Provider\Fixtures;

use Silex\Application;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider;

class ControllerProviderMock extends YvesControllerProvider
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function defineControllers(Application $app): void
    {
        $this->createController('/foo', 'foo', 'foo', 'index');
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Braintree\Module;

use Codeception\Module;

class Braintree extends Module
{

    /**
     * @param array $settings
     *
     * @return void
     */
    public function _beforeSuite($settings = [])
    {
        $this->addBraintreeToConfig();
    }

    /**
     * @return void
     */
    public function _afterSuite()
    {
        $this->removeBraintreeFromConfig();
    }

    /**
     * @return void
     */
    protected function addBraintreeToConfig()
    {
        $configLocalTest = $this->getPathToConfigLocalTest();
        $braintreeConfig = realpath(__DIR__ . '/../../../config/config.dist.php');
        file_put_contents($configLocalTest, file_get_contents($braintreeConfig));
    }

    /**
     * @return string
     */
    protected function getPathToConfigLocalTest()
    {
        return APPLICATION_ROOT_DIR . '/config/Shared/config_local_test.php';
    }

    /**
     * @return void
     */
    protected function removeBraintreeFromConfig()
    {
        $configFile = $this->getPathToConfigLocalTest();

        if (file_exists($configFile)) {
            unlink($configFile);
        }
    }

}

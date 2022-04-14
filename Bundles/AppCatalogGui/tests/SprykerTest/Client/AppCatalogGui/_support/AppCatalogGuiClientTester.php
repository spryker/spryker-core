<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\AppCatalogGui;

use Codeception\Actor;

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
 * @method \Spryker\Client\AppCatalogGui\AppCatalogGuiClientInterface getClient()
 *
 * @SuppressWarnings(PHPMD)
 */
class AppCatalogGuiClientTester extends Actor
{
    use _generated\AppCatalogGuiClientTesterActions;

    /**
     * @param string|null $aopIdpUrl
     *
     * @return void
     */
    public function mockAopClientConfig(?string $aopIdpUrl = 'url'): void
    {
        $this->mockConfigMethod('getAopIdpUrl', $aopIdpUrl);
        $this->mockConfigMethod('getAopClientId', 'client_id');
        $this->mockConfigMethod('getAopClientSecret', 'client_secret');
        $this->mockConfigMethod('getAopAudience', 'aop_audience');
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getFixture(string $fileName): string
    {
        return file_get_contents(codecept_data_dir('Fixtures/' . $fileName));
    }
}

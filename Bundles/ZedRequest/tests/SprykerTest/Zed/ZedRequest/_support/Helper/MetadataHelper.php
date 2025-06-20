<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Client\Currency\Plugin\ZedRequestMetaDataProviderPlugin;
use Spryker\Client\Locale\Plugin\ZedRequest\LocaleMetaDataProviderPlugin;
use Spryker\Client\Store\Plugin\ZedRequest\StoreMetaDataProviderPlugin;
use Spryker\Client\ZedRequest\ZedRequestDependencyProvider;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;

class MetadataHelper extends Module
{
    use DependencyHelperTrait;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->setDependency(ZedRequestDependencyProvider::META_DATA_PROVIDER_PLUGINS, [
            'currency' => new ZedRequestMetaDataProviderPlugin(),
            'store' => new StoreMetaDataProviderPlugin(),
            'locale' => new LocaleMetaDataProviderPlugin(),
        ]);
    }
}

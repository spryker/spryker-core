<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Kernel;

use PHPUnit_Framework_TestCase;
use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Client\Kernel\BundleConfigResolverAwareTrait;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Kernel
 * @group BundleConfigResolverAwareTraitTest
 */
class BundleConfigResolverAwareTraitTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSetConfigMustReturnFluentInterface()
    {
        $bundleConfigResolverAwareTraitMock = $this->getBundleConfigResolverAwareTraitMock();
        $returned = $bundleConfigResolverAwareTraitMock->setConfig(
            $this->getAbstractBundleConfigMock()
        );

        $this->assertSame($bundleConfigResolverAwareTraitMock, $returned);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Kernel\BundleConfigResolverAwareTrait
     */
    private function getBundleConfigResolverAwareTraitMock()
    {
        return $this->getMockForTrait(BundleConfigResolverAwareTrait::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Kernel\AbstractBundleConfig
     */
    private function getAbstractBundleConfigMock()
    {
        return $this->getMockForAbstractClass(AbstractBundleConfig::class);
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\Kernel;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Service\Kernel\BundleConfigResolverAwareTrait;

/**
 * @group Unit
 * @group Spryker
 * @group Service
 * @group Kernel
 * @group BundleConfigResolverAwareTraitTest
 */
class BundleConfigResolverAwareTraitTest extends \PHPUnit_Framework_TestCase
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Service\Kernel\BundleConfigResolverAwareTrait
     */
    private function getBundleConfigResolverAwareTraitMock()
    {
        return $this->getMockForTrait(BundleConfigResolverAwareTrait::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Service\Kernel\AbstractBundleConfig
     */
    private function getAbstractBundleConfigMock()
    {
        return $this->getMockForAbstractClass(AbstractBundleConfig::class);
    }

}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel;

use Codeception\Test\Unit;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Glue\Kernel\BundleConfigResolverAwareTrait;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group BundleConfigResolverAwareTraitTest
 * Add your own group annotations below this line
 */
class BundleConfigResolverAwareTraitTest extends Unit
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Glue\Kernel\BundleConfigResolverAwareTrait
     */
    private function getBundleConfigResolverAwareTraitMock()
    {
        return $this->getMockForTrait(BundleConfigResolverAwareTrait::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Glue\Kernel\AbstractBundleConfig
     */
    private function getAbstractBundleConfigMock()
    {
        return $this->getMockForAbstractClass(AbstractBundleConfig::class);
    }
}

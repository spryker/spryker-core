<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use Spryker\Yves\Kernel\BundleConfigResolverAwareTrait;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\BundleConfigResolverAwareTrait
     */
    private function getBundleConfigResolverAwareTraitMock()
    {
        return $this->getMockForTrait(BundleConfigResolverAwareTrait::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\AbstractBundleConfig
     */
    private function getAbstractBundleConfigMock()
    {
        return $this->getMockForAbstractClass(AbstractBundleConfig::class);
    }
}

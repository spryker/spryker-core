<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Communication;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Communication\BundleControllerAction;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group BundleControllerActionTest
 * Add your own group annotations below this line
 */
class BundleControllerActionTest extends Unit
{
    /**
     * @var string
     */
    public const BUNDLE = 'foo';

    /**
     * @var string
     */
    public const CONTROLLER = 'bar';

    /**
     * @var string
     */
    public const ACTION = 'baz';

    /**
     * @return void
     */
    public function testGetBundleShouldReturnBundleNameFromRequest(): void
    {
        $bundleControllerAction = $this->getBundleControllerAction();

        $this->assertSame(static::BUNDLE, $bundleControllerAction->getBundle());
    }

    /**
     * @return void
     */
    public function testGetControllerShouldReturnControllerNameFromRequest(): void
    {
        $bundleControllerAction = $this->getBundleControllerAction();

        $this->assertSame(static::CONTROLLER, $bundleControllerAction->getController());
    }

    /**
     * @return void
     */
    public function testGetActionShouldReturnActionNameFromRequest(): void
    {
        $bundleControllerAction = $this->getBundleControllerAction();

        $this->assertSame(static::ACTION, $bundleControllerAction->getAction());
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\BundleControllerAction
     */
    private function getBundleControllerAction(): BundleControllerAction
    {
        $request = $this->getRequestTestObject();
        $bundleControllerAction = new BundleControllerAction(
            $request->attributes->get('module'),
            $request->attributes->get('controller'),
            $request->attributes->get('action'),
        );

        return $bundleControllerAction;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequestTestObject(): Request
    {
        $request = new Request(
            [],
            [],
            ['module' => static::BUNDLE, 'controller' => static::CONTROLLER, 'action' => static::ACTION],
        );

        return $request;
    }
}

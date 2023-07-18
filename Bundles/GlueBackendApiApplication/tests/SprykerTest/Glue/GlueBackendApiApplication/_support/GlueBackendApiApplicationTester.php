<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueBackendApiApplication;

use Codeception\Actor;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;

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
 *
 * @SuppressWarnings(\SprykerTest\Glue\GlueBackendApiApplication\PHPMD)
 */
class GlueBackendApiApplicationTester extends Actor
{
    use _generated\GlueBackendApiApplicationTesterActions;

    /**
     * @var string
     */
    public const GET_METHOD_NAME = 'get';

    /**
     * @var string
     */
    public const GET_METHOD_SCOPE = 'backend:test:read';

    /**
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function createApiApplicationSchemaContextTransfer(): ApiApplicationSchemaContextTransfer
    {
        return new ApiApplicationSchemaContextTransfer();
    }

    /**
     * @return \SprykerTest\Glue\GlueBackendApiApplication\TestAbstractRouteProviderPlugin
     */
    public function createTestAbstractRouteProviderPlugin(): TestAbstractRouteProviderPlugin
    {
        return new TestAbstractRouteProviderPlugin();
    }
}

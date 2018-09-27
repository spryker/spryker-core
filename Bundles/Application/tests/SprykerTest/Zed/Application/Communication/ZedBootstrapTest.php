<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Communication;

use Codeception\Test\Unit;
use Spryker\Zed\Application\Communication\ZedBootstrap;

/**
 * Auto-generated group annotations
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Communication
 * @group ZedBootstrapTest
 * Add your own group annotations below this line
 */
class ZedBootstrapTest extends Unit
{
    public const HTTP_X_INTERNAL_REQUEST = 'HTTP_X_INTERNAL_REQUEST';

    public const REGISTER_SERVICE_PROVIDER = 'registerServiceProvider';
    public const REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST = 'registerServiceProviderForInternalRequest';
    public const REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST_WITH_AUTHENTICATION = 'registerServiceProviderForInternalRequestWithAuthentication';
    public const ADD_VARIABLES_TO_TWIG = 'addVariablesToTwig';
    public const IS_AUTHENTICATION_ENABLED = 'isAuthenticationEnabled';

    /**
     * @return void
     */
    public function testDefaultServiceProvidersWillRegister()
    {
        $zedBootstrapMock = $this->createZedBootstrapMock();
        $zedBootstrapMock->method(self::IS_AUTHENTICATION_ENABLED)->willReturn(true);

        $zedBootstrapMock->expects($this->once())->method(self::REGISTER_SERVICE_PROVIDER);
        $zedBootstrapMock->expects($this->never())->method(self::REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST);
        $zedBootstrapMock->expects($this->never())->method(self::REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST_WITH_AUTHENTICATION);
        $zedBootstrapMock->boot();
    }

    /**
     * @return void
     */
    public function testInternalRequestServiceProvidersWillRegister()
    {
        $_SERVER[self::HTTP_X_INTERNAL_REQUEST] = 1;
        $zedBootstrapMock = $this->createZedBootstrapMock();
        $zedBootstrapMock->method(self::IS_AUTHENTICATION_ENABLED)->willReturn(true);

        $zedBootstrapMock->expects($this->never())->method(self::REGISTER_SERVICE_PROVIDER);
        $zedBootstrapMock->expects($this->never())->method(self::REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST);
        $zedBootstrapMock->expects($this->once())->method(self::REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST_WITH_AUTHENTICATION);
        $zedBootstrapMock->boot();
    }

    /**
     * @return void
     */
    public function testInternalRequestServiceProvidersWithoutAuthenticationWillRegister()
    {
        $_SERVER[self::HTTP_X_INTERNAL_REQUEST] = 1;
        $zedBootstrapMock = $this->createZedBootstrapMock();
        $zedBootstrapMock->method(self::IS_AUTHENTICATION_ENABLED)->willReturn(false);

        $zedBootstrapMock->expects($this->never())->method(self::REGISTER_SERVICE_PROVIDER);
        $zedBootstrapMock->expects($this->once())->method(self::REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST);
        $zedBootstrapMock->expects($this->never())->method(self::REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST_WITH_AUTHENTICATION);
        $zedBootstrapMock->boot();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Application\Communication\ZedBootstrap
     */
    protected function createZedBootstrapMock()
    {
        return $this->getMockBuilder(ZedBootstrap::class)->setMethods([
            self::REGISTER_SERVICE_PROVIDER,
            self::REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST,
            self::REGISTER_SERVICE_PROVIDER_FOR_INTERNAL_REQUEST_WITH_AUTHENTICATION,
            self::ADD_VARIABLES_TO_TWIG,
            self::IS_AUTHENTICATION_ENABLED,
        ])->getMock();
    }
}

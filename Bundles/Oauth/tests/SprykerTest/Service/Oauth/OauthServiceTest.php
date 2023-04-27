<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Oauth;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Oauth
 * @group OauthServiceTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Service\Oauth\OauthServiceTester $tester
 */
class OauthServiceTest extends Unit
{
    /**
     * @var string
     */
    protected const JWT_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJmcm9udGVuZCIsImp0aSI6ImM2ZjVjZmU0NWRmM2
        M4Y2MyODBjMDE2ZThlNzJiMmJmMmM5NjVmZDBhYWRiMWJhYzEwOGVmZWU5MDM0ZDgyODU4NGE2NjhmZDUzOGY0NmU4IiwiaWF0IjoxNjgwMDAwNj
        YxLjgyNzI5NiwibmJmIjoxNjgwMDAwNjYxLjgyNzI5NzksImV4cCI6MTY4MDAyOTQ2MS42OTgwNiwic3ViIjoie1widXNlcl9yZWZlcmVuY2VcIj
        pudWxsLFwiaWRfdXNlclwiOjQsXCJ1dWlkXCI6XCI4NGI4Zjg0Ny1kNzU1LTU3NjMtYjEyOC04ZTEzOGU2NTcxZmNcIn0iLCJzY29wZXMiOlsidX
        NlciJdfQ.dgspmFwvGcj0AFSbiti5e7fqI-gjY-7yjwAj67ouuTEV-dM_vzTmQ4zVQza0TVoId2Uq5z66SNd--HLq-PB9lMYTw4hdLGkC7-GggRZ
        mk6ABMzn-UfCpfgqHqcYRS0AIdPZN6u35PEeuf2WBmp8RGM_PUlNzD0KOObhVA4R786SI1mQtFc3CEhBDWU8rWeNLh5HzYmK12YsWdcV07chEuvm
        wjSfa326VfyMl63ARa9O4HSc27p8qyMM7eK15SsrbsDIQoJxk48_mTpcWjRHxULMD6E9fjq_E6GrKpkBTWlT8s-FoK4ZNdiK_jdbOGekMf-AGnnH
        oxMYF0gpJOsIbLw';

    /**
     * @var \SprykerTest\Service\Oauth\OauthServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExtractAccessTokenDataReturnsTransferWithCorrectDecodedData(): void
    {
        // Act
        $oauthAccessTokenDataTransfer = $this->tester->getOauthService()->extractAccessTokenData(static::JWT_TOKEN);

        // Assert
        $this->assertSame($oauthAccessTokenDataTransfer->getOauthIssuedAt(), 1680000661);
        $this->assertSame(
            $oauthAccessTokenDataTransfer->getOauthAccessTokenId(),
            'c6f5cfe45df3c8cc280c016e8e72b2bf2c965fd0aadb1bac108efee9034d828584a668fd538f46e8',
        );
        $this->assertSame($oauthAccessTokenDataTransfer->getOauthClientId(), 'frontend');
        $this->assertSame(
            $oauthAccessTokenDataTransfer->getOauthUserId(),
            '{"user_reference":null,"id_user":4,"uuid":"84b8f847-d755-5763-b128-8e138e6571fc"}',
        );
        $this->assertSame($oauthAccessTokenDataTransfer->getOauthScopes(), ['user']);
    }
}

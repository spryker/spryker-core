<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OauthBackendApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\OauthBackendApi\Plugin\UserRequestBuilderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OauthBackendApi
 * @group Plugin
 * @group UserRequestBuilderPluginTest
 * Add your own group annotations below this line
 */
class UserRequestBuilderPluginTest extends Unit
{
    /**
     * @var string
     */
    public const HTTP_AUTHORIZATION = 'HTTP_AUTHORIZATION';

    /**
     * @var \SprykerTest\Glue\OauthBackendApi\OauthBackendApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUserFinderWhenAuthorizationHeaderNotExist(): void
    {
        //Act
        $glueRequestTransfer = $this->findUser(new GlueRequestTransfer());

        //Assert
        $this->assertEmpty($glueRequestTransfer->getRequestUser());
    }

    /**
     * @return void
     */
    public function testUserFinderWhenAccessTokenIsEmpty(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => '']]);

        //Act
        $glueRequestTransfer = $this->findUser($glueRequestTransfer);

        //Assert
        $this->assertIsObject($glueRequestTransfer);
        $this->assertEmpty($glueRequestTransfer->getRequestUser());
    }

    /**
     * @return void
     */
    public function testUserFinderWithWrongAccessToken(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => 'Bearer eyJ0eXAiOiJKV1QiLC']]);

        //Act
        $glueRequestTransfer = $this->findUser($glueRequestTransfer);

        //Assert
        $this->assertIsObject($glueRequestTransfer);
        $this->assertEmpty($glueRequestTransfer->getRequestUser());
    }

    /**
     * @return void
     */
    public function testUserFinderWhenAccessTokenIsValid(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setMeta(['authorization' => [0 => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJmcm9udGVuZCIsImp0aSI6ImI1MjRhOTIwNjBlMjUzODE2Y2RkMzVlYzljMTRlYTk4MzA0M2MzYjMyM2YyYjBjNzNiMThjNzZiNGQ0OWU3N2QwZGVkYWI5NTIyYjIxODQ1IiwiaWF0IjoxNjUxNzA0MzQwLjkzMDg5ODksIm5iZiI6MTY1MTcwNDM0MC45MzA5NjQ5LCJleHAiOjE2NTE3MzMxNDAuNjM3MDE1MSwic3ViIjoie1widXNlcl9yZWZlcmVuY2VcIjpudWxsLFwiaWRfdXNlclwiOjF9Iiwic2NvcGVzIjpbImN1c3RvbWVyIiwidXNlciJdfQ.ThoffHjhWhUItJVF8MhnTiC1Osbew1vXXsWa-EBBnPsaqB3R7EaCbxfrmNUOK3CjwSO2yIXmLMPpcr7bVoTcC9yUTP9qQgePIi5-l4moonyUGJMJsNdEK_t0zia-lt0lOHaDWjn1hKNouaYwF3orGNzI_RBDsPM9YUh0WYUWemwjT8jpshb2aoDalumiynT8ecbdZocTMZkYNGwji1_L4-v0dVobRZolylWIsGsENb-nvJjbR40sDzsmRz9E9cfrkQEEGwFSDRweYJf72k2fzNScx2ZEVpVA1xxS9asbL2mRJ0YCzA5gYJf5NjNC5_9tGK5bAAxIbSj7xaPpdkEnRg']]);

        //Act
        $glueRequestTransfer = $this->findUser($glueRequestTransfer);

        //Assert
        $this->assertIsObject($glueRequestTransfer);
        $this->assertNotEmpty($glueRequestTransfer->getRequestUser());
        $this->assertSame(1, $glueRequestTransfer->getRequestUser()->getSurrogateIdentifier());
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function findUser(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $accessTokenUserFinderPlugin = new UserRequestBuilderPlugin();

        return $accessTokenUserFinderPlugin->build($glueRequestTransfer);
    }
}

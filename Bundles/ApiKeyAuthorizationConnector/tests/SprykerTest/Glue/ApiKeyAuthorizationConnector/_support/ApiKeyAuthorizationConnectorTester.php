<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\ApiKeyAuthorizationConnector;

use Codeception\Actor;
use DateTime;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Orm\Zed\ApiKey\Persistence\SpyApiKey;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Glue\ApiKeyAuthorizationConnector\PHPMD)
 */
class ApiKeyAuthorizationConnectorTester extends Actor
{
    use _generated\ApiKeyAuthorizationConnectorTesterActions;

    /**
     * @var string
     */
    public const FOO_KEY = 'test';

    /**
     * @var string
     */
    public const EXPIRED_API_KEY = 'expiredtestkey';

    /**
     * @var string
     */
    public const NOT_EXPIRED_API_KEY = 'noexpiredtestkey';

    /**
     * @var string
     */
    protected const EXPIRED_KEY_NAME = 'EXPIRED';

    /**
     * @var string
     */
    protected const NOT_EXPIRED_KEY_NAME = 'NO_EXPIRED';

    /**
     * @var string
     */
    protected const API_KEY_REQUEST_HEADER = 'x-api-key';

    /**
     * @var string
     */
    protected const API_KEY_REQUEST_QUERY_PARAM = 'api_key';

    /**
     * @var string
     */
    protected const FOO_KEY_NAME = 'Foo';

    /**
     * @var string
     */
    protected const BAR_KEY = 'bar_test';

    /**
     * @var string
     */
    protected const FOO_KEY_HASH = '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08';

    /**
     * @var string
     */
    protected const EXPIRED_API_KEY_HASH = '85ed2a56f82aed1644372cf7ff8cc8e9b1f3d46f404af9c2a73fe1828855138a';

    /**
     * @var string
     */
    protected const NOT_EXPIRED_API_KEY_HASH = 'fe4f0f707a786f2b4b5977cb49b2348d0fcb40dd5840a0ea4332c4af337e0c35';

    /**
     * @return void
     */
    public function createFakeApiKeyRecord(): void
    {
        (new SpyApiKey())->setName(static::FOO_KEY_NAME)
            ->setKeyHash(static::FOO_KEY_HASH)
            ->setCreatedBy(1)
            ->save();
    }

    /**
     * @return void
     */
    public function createFakeExpiredApiKeyRecord(): void
    {
        (new SpyApiKey())->setName(static::EXPIRED_KEY_NAME)
            ->setKeyHash(static::EXPIRED_API_KEY_HASH)
            ->setCreatedBy(1)
            ->setValidTo((new DateTime())->modify('-10 day')->format('Y-m-d H:i:s'))
            ->save();
    }

    /**
     * @return void
     */
    public function createFakeNoExpiredApiKeyRecord(): void
    {
        (new SpyApiKey())->setName(static::NOT_EXPIRED_KEY_NAME)
            ->setKeyHash(static::NOT_EXPIRED_API_KEY_HASH)
            ->setCreatedBy(1)
            ->setValidTo((new DateTime())->modify('+10 day')->format('Y-m-d H:i:s'))
            ->save();
    }

    /**
     * @return \Generated\Shared\Transfer\AuthorizationRequestTransfer
     */
    public function getAuthorizationRequestTransferWithIdentity(): AuthorizationRequestTransfer
    {
        $identityTransfer = new AuthorizationIdentityTransfer();

        return (new AuthorizationRequestTransfer())
            ->setIdentity($identityTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function getGlueRequestTransfer(): GlueRequestTransfer
    {
        return new GlueRequestTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function getGlueRequestWithApiKeyHeader(): GlueRequestTransfer
    {
        $glueRequestTransfer = $this->getGlueRequestTransfer();
        $glueRequestTransfer->setMeta([
            static::API_KEY_REQUEST_HEADER => [static::FOO_KEY],
        ]);

        return $glueRequestTransfer;
    }

    /**
     * @param string $apiKey
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function getGlueRequestWithApiKeyQueryParam(string $apiKey): GlueRequestTransfer
    {
        $glueRequestTransfer = $this->getGlueRequestTransfer();
        $glueRequestTransfer->setQueryFields([
            static::API_KEY_REQUEST_QUERY_PARAM => $apiKey,
        ]);

        return $glueRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function getGlueRequestWithExpiredApiKeyQueryParam(): GlueRequestTransfer
    {
        $glueRequestTransfer = $this->getGlueRequestTransfer();
        $glueRequestTransfer->setQueryFields([
            static::API_KEY_REQUEST_QUERY_PARAM => static::EXPIRED_API_KEY,
        ]);

        return $glueRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function getGlueRequestWithNoExpiredApiKeyQueryParam(): GlueRequestTransfer
    {
        $glueRequestTransfer = $this->getGlueRequestTransfer();
        $glueRequestTransfer->setQueryFields([
            static::API_KEY_REQUEST_QUERY_PARAM => static::NOT_EXPIRED_API_KEY,
        ]);

        return $glueRequestTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function getGlueRequestWithWrongApiKey(): GlueRequestTransfer
    {
        $glueRequestTransfer = $this->getGlueRequestTransfer();
        $glueRequestTransfer->setQueryFields([
            static::API_KEY_REQUEST_QUERY_PARAM => static::BAR_KEY,
        ]);

        return $glueRequestTransfer;
    }
}

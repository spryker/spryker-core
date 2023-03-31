<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AuthenticationOauth;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\GlueAuthenticationRequestBuilder;
use Generated\Shared\DataBuilder\OauthRequestBuilder;
use Generated\Shared\Transfer\ApiTokenAttributesTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestContextTransfer;
use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Store\StoreDependencyProvider as ClientStoreDependencyProvider;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;

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
 * @SuppressWarnings(PHPMD)
 */
class AuthenticationOauthBusinessTester extends Actor
{
    use _generated\AuthenticationOauthBusinessTesterActions;

    /**
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'GLUE_BACKEND_API_APPLICATION';

    /**
     * @var string
     */
    protected const TEST_GRANT_TYPE = 'password';

    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'change123';

    /**
     * @var string
     */
    protected const DEFAULT_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->setDependency(ClientStoreDependencyProvider::PLUGINS_STORE_EXPANDER, [
            $this->createStoreStorageStoreExpanderPluginMock(),
        ]);
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer
     */
    public function haveGlueAuthenticationRequestTransfer(string $username): GlueAuthenticationRequestTransfer
    {
        $glueAuthenticationRequestContextTransfer = (new GlueAuthenticationRequestContextTransfer())
            ->setRequestApplication(static::GLUE_BACKEND_API_APPLICATION);

        return (new GlueAuthenticationRequestBuilder(
            [
                GlueAuthenticationRequestTransfer::OAUTH_REQUEST => $this->haveOauthRequestTransfer($username),
                GlueAuthenticationRequestTransfer::REQUEST_CONTEXT => $glueAuthenticationRequestContextTransfer,
            ],
        ))->build();
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\OauthRequestTransfer
     */
    protected function haveOauthRequestTransfer(string $username): OauthRequestTransfer
    {
        return (new OauthRequestBuilder(
            [
                ApiTokenAttributesTransfer::GRANT_TYPE => static::TEST_GRANT_TYPE,
                ApiTokenAttributesTransfer::USERNAME => $username,
                ApiTokenAttributesTransfer::PASSWORD => static::TEST_PASSWORD,
            ],
        ))->build();
    }

    /**
     * @return \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE_NAME)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY);

        $storeStorageStoreExpanderPluginMock = Stub::makeEmpty(StoreExpanderPluginInterface::class, [
            'expand' => $storeTransfer,
        ]);

        return $storeStorageStoreExpanderPluginMock;
    }
}

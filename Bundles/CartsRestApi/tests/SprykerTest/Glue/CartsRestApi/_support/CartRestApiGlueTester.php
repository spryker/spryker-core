<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CartsRestApi;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutDataBuilder;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

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
class CartRestApiGlueTester extends Actor
{
    use _generated\CartRestApiGlueTesterActions;

    /**
     * @uses \Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA
     *
     * @var string
     */
    protected const RESOURCE_CHECKOUT_DATA = 'checkout-data';

    /**
     * @uses \Spryker\Shared\PersistentCart\PersistentCartConfig::PERSISTENT_CART_ANONYMOUS_PREFIX
     *
     * @var string
     */
    protected const PERSISTENT_CART_ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @var string
     */
    protected const REST_USER_IDENTIFIER = 'test_rest_user_identifier';

    /**
     * @var string
     */
    protected const LOCALE_NAME_DE = 'DE';

    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\RestCheckoutDataTransfer
     */
    public function getRestCheckoutDataTransfer(array $data = []): RestCheckoutDataTransfer
    {
        return (new RestCheckoutDataBuilder($data))->build();
    }

    /**
     * @param string $quoteUuid
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(string $quoteUuid): QuoteTransfer
    {
        return (new QuoteBuilder([
            QuoteTransfer::UUID => $quoteUuid,
            QuoteTransfer::CURRENCY => (new CurrencyBuilder())->build(),
            QuoteTransfer::STORE => (new StoreBuilder())->build(),
        ]))->build();
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCheckoutDataRestResource(): RestResourceInterface
    {
        return new RestResource(static::RESOURCE_CHECKOUT_DATA);
    }

    /**
     * @param bool $isAuthorisedUser
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function getRestRequestMock(bool $isAuthorisedUser = false): RestRequestInterface
    {
        $userNaturalIdentifier = $isAuthorisedUser ?
            static::REST_USER_IDENTIFIER :
            static::PERSISTENT_CART_ANONYMOUS_PREFIX . static::REST_USER_IDENTIFIER;

        return Stub::makeEmpty(
            RestRequestInterface::class,
            [
                'getMetadata' => $this->getMetadataMock(),
                'getRestUser' => (new RestUserTransfer())->setNaturalIdentifier($userNaturalIdentifier),
                'getHttpRequest' => $this->getRequestMock($isAuthorisedUser),
            ],
        );
    }

    /**
     * @param bool $isAuthorisedUser
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestMock(bool $isAuthorisedUser): Request
    {
        return Stub::makeEmpty(
            Request::class,
            [
                'headers' => $this->getHeaderMock($isAuthorisedUser),
            ],
        );
    }

    /**
     * @param bool $isAuthorisedUser
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\HeaderBag
     */
    protected function getHeaderMock(bool $isAuthorisedUser): HeaderBag
    {
        return Stub::makeEmpty(
            HeaderBag::class,
            [
                'has' => function (string $authorizationHeader) use ($isAuthorisedUser) {
                    return $authorizationHeader === CartsRestApiConfig::HEADER_AUTHORIZATION && $isAuthorisedUser ||
                        $authorizationHeader === CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID && !$isAuthorisedUser;
                },
            ],
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface
     */
    protected function getMetadataMock(): MetadataInterface
    {
        return Stub::makeEmpty(
            MetadataInterface::class,
            [
                'getLocale' => static::LOCALE_NAME_DE,
            ],
        );
    }
}

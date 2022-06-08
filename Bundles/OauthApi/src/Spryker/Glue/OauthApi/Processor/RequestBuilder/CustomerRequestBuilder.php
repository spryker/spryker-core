<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi\Processor\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestCustomerTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Spryker\Glue\OauthApi\Dependency\Service\OauthApiToOauthServiceInterface;
use Spryker\Glue\OauthApi\Dependency\Service\OauthApiToUtilEncodingServiceInterface;
use Spryker\Glue\OauthApi\Processor\Extractor\AccessTokenExtractorInterface;

class CustomerRequestBuilder implements CustomerRequestBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_CUSTOMER_REFERENCE = 'customer_reference';

    /**
     * @var string
     */
    protected const KEY_ID_CUSTOMER = 'id_customer';

    /**
     * @var \Spryker\Glue\OauthApi\Dependency\Service\OauthApiToOauthServiceInterface
     */
    protected $oauthService;

    /**
     * @var \Spryker\Glue\OauthApi\Dependency\Service\OauthApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Glue\OauthApi\Processor\Extractor\AccessTokenExtractorInterface
     */
    protected $accessTokenExtractor;

    /**
     * @param \Spryker\Glue\OauthApi\Dependency\Service\OauthApiToOauthServiceInterface $oauthService
     * @param \Spryker\Glue\OauthApi\Dependency\Service\OauthApiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Glue\OauthApi\Processor\Extractor\AccessTokenExtractorInterface $accessTokenExtractor
     */
    public function __construct(
        OauthApiToOauthServiceInterface $oauthService,
        OauthApiToUtilEncodingServiceInterface $utilEncodingService,
        AccessTokenExtractorInterface $accessTokenExtractor
    ) {
        $this->oauthService = $oauthService;
        $this->utilEncodingService = $utilEncodingService;
        $this->accessTokenExtractor = $accessTokenExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $oauthAccessTokenDataTransfer = $this->findCustomerByAccessToken($glueRequestTransfer);

        if (!$oauthAccessTokenDataTransfer || !$oauthAccessTokenDataTransfer->getOauthUserId()) {
            return $glueRequestTransfer;
        }

        return $this->mapRequestCustomerTransfer($oauthAccessTokenDataTransfer, $glueRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer|null
     */
    protected function findCustomerByAccessToken(GlueRequestTransfer $glueRequestTransfer): ?OauthAccessTokenDataTransfer
    {
        $accessTokenData = $this->accessTokenExtractor->extract($glueRequestTransfer);
        if (!$accessTokenData) {
            return null;
        }

        return $this->oauthService->extractAccessTokenData($accessTokenData[1]);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function mapRequestCustomerTransfer(
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueRequestTransfer {
        /** @var array<string, mixed> $customerIdentifier */
        $customerIdentifier = $this->utilEncodingService->decodeJson(
            $oauthAccessTokenDataTransfer->getOauthUserIdOrFail(),
            true,
        );

        if (!isset($customerIdentifier[static::KEY_CUSTOMER_REFERENCE]) && !isset($customerIdentifier[static::KEY_ID_CUSTOMER])) {
            return $glueRequestTransfer;
        }

        $glueRequestCustomerTransfer = (new GlueRequestCustomerTransfer())
            ->setNaturalIdentifier($customerIdentifier[static::KEY_CUSTOMER_REFERENCE])
            ->setSurrogateIdentifier($customerIdentifier[static::KEY_ID_CUSTOMER])
            ->setScopes($oauthAccessTokenDataTransfer->getOauthScopes());

        return $glueRequestTransfer->setRequestCustomer($glueRequestCustomerTransfer);
    }
}

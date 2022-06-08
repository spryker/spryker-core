<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui\Communication\Mapper;

use Generated\Shared\Transfer\AccessTokenResponseTransfer;

interface OauthClientResponseTransferToResponseDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AccessTokenResponseTransfer $accessTokenResponseTransfer
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function mapFailedOauthClientResponseTransferToResponseErrorData(
        AccessTokenResponseTransfer $accessTokenResponseTransfer,
        array $data
    ): array;

    /**
     * @param \Generated\Shared\Transfer\AccessTokenResponseTransfer $accessTokenResponseTransfer
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function mapSuccessOauthClientResponseTransferToResponseAccessTokenData(
        AccessTokenResponseTransfer $accessTokenResponseTransfer,
        array $data
    ): array;
}

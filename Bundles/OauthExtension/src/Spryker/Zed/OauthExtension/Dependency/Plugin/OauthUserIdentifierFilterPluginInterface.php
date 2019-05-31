<?php

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

interface OauthUserIdentifierFilterPluginInterface
{
    /**
     * Specification:
     * - TODO:
     *
     * @api
     *
     * @param array $userIdentifier
     *
     * @return array
     */
    public function filter(array $userIdentifier): array;
}

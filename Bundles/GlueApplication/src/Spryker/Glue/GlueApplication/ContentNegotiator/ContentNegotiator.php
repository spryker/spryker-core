<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ContentNegotiator;

use Generated\Shared\Transfer\GlueRequestTransfer;

class ContentNegotiator implements ContentNegotiatorInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_CONTENT_TYPE_WEIGHT = '1';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT = 'accept';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'content-type';

    /**
     * @var string
     */
    protected const SEPARATOR_CONTENT_TYPES = ',';

    /**
     * @var string
     */
    protected const SEPARATOR_SLASH = '/';

    /**
     * @var string
     */
    protected const SEPARATOR_WEIGHT = ';q=';

    /**
     * @var string
     */
    protected const WILDCARD_APPLICATION_TYPE = '*/';

    /**
     * @var string
     */
    protected const WILDCARD_APPLICATION_SUBTYPE = '/*';

    /**
     * @var string
     */
    protected const WILDCARD_APPLICATION_FULL = '*/*';

    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface>
     */
    protected array $apiConventionPlugins;

    /**
     * @var array<\Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface>
     */
    protected array $defaultEncoderStrategies;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface> $apiConventionPlugins
     * @param array<\Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface> $defaultEncoderStrategies
     */
    public function __construct(array $apiConventionPlugins, array $defaultEncoderStrategies)
    {
        $this->apiConventionPlugins = $apiConventionPlugins;
        $this->defaultEncoderStrategies = $defaultEncoderStrategies;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function negotiate(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        foreach ($this->apiConventionPlugins as $conventionPlugin) {
            if ($conventionPlugin->isApplicable($glueRequestTransfer)) {
                $glueRequestTransfer->setConvention($conventionPlugin->getName());
            }
        }

        $contentTypeHeader = $this->resolveHeaderValue($glueRequestTransfer, static::HEADER_CONTENT_TYPE);
        $glueRequestTransfer->setRequestedFormat($contentTypeHeader);

        if ($glueRequestTransfer->getConvention() === null) {
            $glueRequestTransfer->setAcceptedFormat($this->resolveAcceptedType($glueRequestTransfer));
        }

        return $glueRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string|null
     */
    protected function resolveAcceptedType(GlueRequestTransfer $glueRequestTransfer): ?string
    {
        $acceptHeader = $this->resolveHeaderValue($glueRequestTransfer, static::HEADER_ACCEPT);
        if ($acceptHeader === null) {
            return null;
        }

        $acceptTypesByRank = $this->getAcceptTypesByRank($acceptHeader);

        return $this->resolveAcceptedFromRankedTypes($acceptTypesByRank);
    }

    /**
     * @param string $acceptHeader
     *
     * @return array<string, array<string>>
     */
    protected function getAcceptTypesByRank(string $acceptHeader): array
    {
        $acceptTypesByRank = [];
        /** @phpstan-var array<int, string> */
        $acceptTypes = explode(static::SEPARATOR_CONTENT_TYPES, $acceptHeader);

        foreach ($acceptTypes as $acceptType) {
            /** @phpstan-var array<int, string> */
            $type = explode(static::SEPARATOR_WEIGHT, $acceptType);
            $weight = $type[1] ?? static::DEFAULT_CONTENT_TYPE_WEIGHT;

            $acceptTypesByRank[$weight][] = trim($type[0]);
        }

        return $acceptTypesByRank;
    }

    /**
     * @param array<string, array<string>> $acceptTypesByRank
     *
     * @return string|null
     */
    protected function resolveAcceptedFromRankedTypes(array $acceptTypesByRank): ?string
    {
        krsort($acceptTypesByRank, SORT_NUMERIC);

        $isWildCardRequested = false;
        $requestedWildcards = [];
        foreach ($acceptTypesByRank as $groupedType) {
            foreach ($groupedType as $type) {
                if ($this->isSupportedType($type)) {
                    return $type;
                }

                if ($this->isWildcardType($type)) {
                    $isWildCardRequested = true;
                    $requestedWildcards[] = $type;
                }
            }
        }

        if (!$isWildCardRequested) {
            return null;
        }

        foreach ($requestedWildcards as $requestedWildcard) {
            if ($this->isFullWildCardType($requestedWildcard)) {
                return $this->resolveFirstDefaultType();
            }

            $type = $this->getSameApplicationTypeByWildcard($requestedWildcard);
            if ($type !== null) {
                return $type;
            }
        }

        return null;
    }

    /**
     * @param string $wildcardType
     *
     * @return string|null
     */
    protected function getSameApplicationTypeByWildcard(string $wildcardType): ?string
    {
        foreach ($this->defaultEncoderStrategies as $defaultEncoderStrategy) {
            $acceptedType = $defaultEncoderStrategy->getAcceptedType();

            if ($this->isSameApplicationType($acceptedType, $wildcardType)) {
                return $acceptedType;
            }
        }

        return null;
    }

    /**
     * @param string $haystackType
     * @param string $needleType
     *
     * @return bool
     */
    protected function isSameApplicationType(string $haystackType, string $needleType): bool
    {
        /** @phpstan-var array<int, string> */
        $haystackTypeSplit = explode(static::SEPARATOR_SLASH, $haystackType);
        /** @phpstan-var array<int, string> */
        $needleTypeSplit = explode(static::SEPARATOR_SLASH, $needleType);

        return $haystackTypeSplit[0] === $needleTypeSplit[0];
    }

    /**
     * @return string|null
     */
    protected function resolveFirstDefaultType(): ?string
    {
        if ($this->defaultEncoderStrategies === []) {
            return null;
        }

        return $this->defaultEncoderStrategies[0]->getAcceptedType();
    }

    /**
     * @param string $contentType
     *
     * @return bool
     */
    protected function isSupportedType(string $contentType): bool
    {
        foreach ($this->defaultEncoderStrategies as $defaultEncoderStrategy) {
            if ($defaultEncoderStrategy->getAcceptedType() == $contentType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $contentType
     *
     * @return bool
     */
    protected function isWildCardType(string $contentType): bool
    {
        return strpos($contentType, static::WILDCARD_APPLICATION_TYPE) !== false
            || strpos($contentType, static::WILDCARD_APPLICATION_SUBTYPE) !== false;
    }

    /**
     * @param string $contentType
     *
     * @return bool
     */
    protected function isFullWildCardType(string $contentType): bool
    {
        return ($contentType === static::WILDCARD_APPLICATION_FULL);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param string $header
     *
     * @return string|null
     */
    protected function resolveHeaderValue(GlueRequestTransfer $glueRequestTransfer, string $header): ?string
    {
        $meta = $glueRequestTransfer->getMeta();

        return isset($meta[$header]) ? is_array($meta[$header]) ? $meta[$header][0] ?? null : null : null;
    }
}

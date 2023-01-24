<?php
// phpcs:disable PSR1.Classes.ClassDeclaration

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Rest;

use Spryker\Glue\GlueApplication\Plugin\Rest\GlueRouterPluginTrait\GlueRouterPluginTraitCommon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;

// symfony/routing: <6.0.0
if (class_exists('\Symfony\Component\Routing\RouteCollectionBuilder\RouteCollectionBuilder')) {
    /**
     * @deprecated Will be removed without replacement. Exists only for BC reasons.
     */
    trait GlueRouterPluginTrait
    {
        use GlueRouterPluginTraitCommon;

        /**
         * @api
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         *
         * @return array
         */
        public function matchRequest(Request $request) /* @phpstan-ignore-line */
        {
            return $this->executeMatchRequest($request);
        }

        /**
         * {@inheritDoc}
         *
         * @api
         *
         * @return \Symfony\Component\Routing\RequestContext
         */
        public function getContext() /* @phpstan-ignore-line */
        {
            return $this->executeGetContext();
        }

        /**
         * {@inheritDoc}
         *
         * @api
         *
         * @param string $name
         * @param array $parameters
         * @param int $referenceType
         *
         * @return string
         */
        public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH) /* @phpstan-ignore-line */
        {
            return $this->executeGenerate($name, $parameters, $referenceType);
        }
    }
} else {
    /**
     * @deprecated Will be removed without replacement. Exists only for BC reasons.
     */
    trait GlueRouterPluginTrait
    {
        use GlueRouterPluginTraitCommon;

        /**
         * @api
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         *
         * @return array
         */
        public function matchRequest(Request $request): array
        {
            return $this->executeMatchRequest($request);
        }

        /**
         * {@inheritDoc}
         *
         * @api
         *
         * @return \Symfony\Component\Routing\RequestContext
         */
        public function getContext(): RequestContext
        {
            return $this->executeGetContext();
        }

        /**
         * {@inheritDoc}
         *
         * @api
         *
         * @param string $name
         * @param array $parameters
         * @param int $referenceType
         *
         * @return string
         */
        public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
        {
            return $this->executeGenerate($name, $parameters, $referenceType);
        }
    }
}

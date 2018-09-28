<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRenderer;

class RestApiDocumentationSecuritySchemeGenerator implements RestApiDocumentationSecuritySchemeGeneratorInterface
{
    protected const DEFAULT_BEARER_AUTH_SCHEME_NAME = 'BearerAuth';
    protected const DEFAULT_BEARER_AUTH_SCHEME_TYPE = 'http';
    protected const DEFAULT_BEARER_AUTH_SCHEME = 'bearer';

    /**
     * @var array
     */
    protected $securitySchemes = [];

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRenderer
     */
    protected $securitySchemeRenderer;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\SecuritySchemeRenderer $securitySchemeRenderer
     */
    public function __construct(SecuritySchemeRenderer $securitySchemeRenderer)
    {
        $this->securitySchemeRenderer = $securitySchemeRenderer;

        $this->addDefaultSecuritySchemes();
    }

    /**
     * @return array
     */
    public function getSecuritySchemes(): array
    {
        ksort($this->securitySchemes);

        return $this->securitySchemes;
    }

    /**
     * @return void
     */
    protected function addDefaultSecuritySchemes(): void
    {
        $this->addDefaultBearerAuthSecurityScheme();
    }

    /**
     * @return void
     */
    protected function addDefaultBearerAuthSecurityScheme(): void
    {
        $bearerAuthSchema = $this->createSecurityScheme(
            static::DEFAULT_BEARER_AUTH_SCHEME_NAME,
            static::DEFAULT_BEARER_AUTH_SCHEME_TYPE,
            static::DEFAULT_BEARER_AUTH_SCHEME
        );

        $this->addSecurityScheme($bearerAuthSchema);
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $scheme
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer
     */
    protected function createSecurityScheme(string $name, string $type, string $scheme): RestApiDocumentationSecuritySchemeTransfer
    {
        $securityScheme = new RestApiDocumentationSecuritySchemeTransfer();
        $securityScheme->setName($name);
        $securityScheme->setType($type);
        $securityScheme->setScheme($scheme);

        return $securityScheme;
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationSecuritySchemeTransfer $securityScheme
     *
     * @return void
     */
    protected function addSecurityScheme(RestApiDocumentationSecuritySchemeTransfer $securityScheme): void
    {
        $this->securitySchemes = array_replace_recursive($this->securitySchemes, $this->securitySchemeRenderer->render($securityScheme));
    }
}

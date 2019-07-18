<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\SecuritySchemeTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SecuritySchemeRendererInterface;

class OpenApiSpecificationSecuritySchemeGenerator implements SecuritySchemeGeneratorInterface
{
    protected const DEFAULT_BEARER_AUTH_SCHEME_NAME = 'BearerAuth';
    protected const DEFAULT_BEARER_AUTH_SCHEME_TYPE = 'http';
    protected const DEFAULT_BEARER_AUTH_SCHEME = 'bearer';

    /**
     * @var array
     */
    protected $securitySchemes = [];

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SecuritySchemeRendererInterface
     */
    protected $securitySchemeRenderer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\SecuritySchemeRendererInterface $securitySchemeRenderer
     */
    public function __construct(SecuritySchemeRendererInterface $securitySchemeRenderer)
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
     * @return \Generated\Shared\Transfer\SecuritySchemeTransfer
     */
    protected function createSecurityScheme(string $name, string $type, string $scheme): SecuritySchemeTransfer
    {
        $securityScheme = new SecuritySchemeTransfer();
        $securityScheme->setName($name);
        $securityScheme->setType($type);
        $securityScheme->setScheme($scheme);

        return $securityScheme;
    }

    /**
     * @param \Generated\Shared\Transfer\SecuritySchemeTransfer $securityScheme
     *
     * @return void
     */
    protected function addSecurityScheme(SecuritySchemeTransfer $securityScheme): void
    {
        $this->securitySchemes = array_replace_recursive($this->securitySchemes, $this->securitySchemeRenderer->render($securityScheme));
    }
}

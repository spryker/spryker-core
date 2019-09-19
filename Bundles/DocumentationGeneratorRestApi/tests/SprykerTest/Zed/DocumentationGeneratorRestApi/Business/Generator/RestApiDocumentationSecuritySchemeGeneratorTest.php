<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Codeception\Test\Unit;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Generator
 * @group RestApiDocumentationSecuritySchemeGeneratorTest
 * Add your own group annotations below this line
 */
class RestApiDocumentationSecuritySchemeGeneratorTest extends Unit
{
    protected const BEARER_AUTH = 'BearerAuth';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator\SecuritySchemeGeneratorInterface
     */
    protected $securitySchemeGenerator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->securitySchemeGenerator = (new DocumentationGeneratorRestApiTestFactory())->createOpenApiSpecificationSecuritySchemeGenerator();
    }

    /**
     * @return void
     */
    public function testGetSecuritySchemesShouldReturnDefaultSecuritySchemes(): void
    {
        $securitySchemes = $this->securitySchemeGenerator->getSecuritySchemes();

        $this->assertNotEmpty($securitySchemes);
        $this->assertArrayHasKey(static::BEARER_AUTH, $securitySchemes);
        $this->assertArraySubset([
            static::BEARER_AUTH => [
                'type' => 'http',
                'scheme' => 'bearer',
            ],
        ], $securitySchemes);
    }
}

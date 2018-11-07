<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Generator;

use Codeception\Test\Unit;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorTestFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group RestApiDocumentationGenerator
 * @group Business
 * @group Generator
 * @group RestApiDocumentationSecuritySchemeGeneratorTest
 * Add your own group annotations below this line
 */
class RestApiDocumentationSecuritySchemeGeneratorTest extends Unit
{
    protected const BEARER_AUTH = 'BearerAuth';

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSecuritySchemeGeneratorInterface
     */
    protected $securitySchemeGenerator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->securitySchemeGenerator = (new RestApiDocumentationGeneratorTestFactory())->createSecuritySchemeGenerator();
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

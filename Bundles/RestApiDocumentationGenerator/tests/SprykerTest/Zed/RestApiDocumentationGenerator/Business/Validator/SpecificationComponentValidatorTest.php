<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Validator;

use Codeception\Test\Unit;

use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathResponseSpecificationComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidator;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorTestFactory;

class SpecificationComponentValidatorTest extends Unit
{
    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface
     */
    protected $validator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = (new RestApiDocumentationGeneratorTestFactory())->createSpecificationComponentValidator();
    }

    /**
     * @return void
     */
    public function testIsValidShouldReturnTrueIfComponentIsValid(): void
    {
        $component = new PathResponseSpecificationComponent();
        $component->setDescription('Test description');
        $component->setCode('0');
        $component->setJsonSchemaRef('/some/schema/ref');

        $isValid = $this->validator->isValid($component);

        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsValidShouldReturnFalseIfComponentIsInvalid(): void
    {
        $component = new PathResponseSpecificationComponent();

        $isValid = $this->validator->isValid($component);

        $this->assertFalse($isValid);
    }
}

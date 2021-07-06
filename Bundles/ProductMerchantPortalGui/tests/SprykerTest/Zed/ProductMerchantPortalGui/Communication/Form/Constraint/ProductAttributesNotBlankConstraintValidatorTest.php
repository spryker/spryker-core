<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAttributesNotBlankConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAttributesNotBlankConstraintValidator;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeBridge;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantPortalGui
 * @group Communication
 * @group Form
 * @group Constraint
 * @group ProductAttributesNotBlankConstraintValidatorTest
 * Add your own group annotations below this line
 */
class ProductAttributesNotBlankConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAttributesNotBlankConstraintValidator
     */
    protected $validator;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeBridge
     */
    private $productAttributeFacade;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\Form
     */
    private $parentForm;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\Form
     */
    private $form;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productAttributeFacade = $this->getMockBuilder(ProductMerchantPortalGuiToProductAttributeFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->parentForm = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->form = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testValidateInvalidAttributes()
    {
        // Arrange
        $attributes = [
            'valid case 1' => ['attribute_name' => 'color', 'attribute_default' => '', 'Locale1' => '', 'Locale2' => 'not empty'],
            'invalid case' => ['attribute_name' => 'brand', 'attribute_default' => '', 'Locale1' => '', 'Locale2' => ''], // expected ind
            'valid case 2' => ['attribute_name' => 'size', 'attribute_default' => '', 'Locale1' => 'not empty', 'Locale2' => ''],
            'valid case 3' => ['attribute_name' => 'height', 'attribute_default' => 'not empty', 'Locale1' => '', 'Locale2' => ''],
        ];

        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setIdProductAbstract(111);

        $oldAttributes = [
            '_' => ['brand' => 'Sony'],
            'Locale1' => ['brand' => 'Samsung'],
            'Locale2' => ['brand' => 'Samsung'],
        ];

        $this->productAttributeFacade->expects($this->once())
            ->method('getProductAbstractAttributeValues')
            ->with($productAbstract->getIdProductAbstract())
            ->willReturn($oldAttributes);

        $this->parentForm->expects($this->once())
            ->method('getData')
            ->with()
            ->willReturn($productAbstract);

        $this->form->expects($this->once())
            ->method('getParent')
            ->with()
            ->willReturn($this->parentForm);

        $constraint = new ProductAttributesNotBlankConstraint($this->productAttributeFacade);

        // Act
        $this->setObject($this->form);
        $this->validator->validate($attributes, $constraint);

        // Assert
        $violations = $this->context->getViolations();

        $this->assertCount(1, $violations);
        $this->assertSame($violations->get(0)->getMessage(), 'Please fill in at least one value');
        $this->assertSame($violations->get(0)->getParameters(), [ 'attributesRowNumber' => 'invalid case']);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraintValidator
     */
    protected function createValidator(): ConstraintValidator
    {
        return new ProductAttributesNotBlankConstraintValidator();
    }
}

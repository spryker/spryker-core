<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraintValidator;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;
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
 * @group ProductAbstractAttributeUniqueCombinationConstraintValidatorTest
 * Add your own group annotations below this line
 */
class ProductAbstractAttributeUniqueCombinationConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraintValidator
     */
    protected $validator;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade
     */
    private $productAttributeFacade;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeBridge
     */
    private $productFacade;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeBridge
     */
    private $translatorFacade;

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

        $this->productFacade = $this->getMockBuilder(ProductMerchantPortalGuiToProductFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->translatorFacade = $this->getMockBuilder(ProductMerchantPortalGuiToTranslatorFacadeInterface::class)
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
    public function testValidateUniqueAttributes(): void
    {
        // Arrange
        $productAbstract = new ProductAbstractTransfer();
        $productAbstract->setIdProductAbstract(111);

        $oldAttributes = [
            '_' => [
                'brand' => 'Sony',
            ],
            'Locale1' => [
                'brand' => 'Samsung',
            ],
            'Locale2' => [
                'brand' => 'Samsung',
            ],
        ];

        $newAttributes = [
            'invalid case' => [
                'attribute_name' => 'brand',
                'attribute_default' => 'Samsung',
                'Locale1' => 'Samsung',
                'Locale2' => 'Samsung',
            ],
            'valid case' => [
                'attribute_name' => 'length',
            ],
        ];

        $constraint = new ProductAbstractAttributeUniqueCombinationConstraint(
            $this->productAttributeFacade,
            $this->productFacade,
            $this->translatorFacade,
        );

        $this->parentForm->expects($this->once())
            ->method('getData')
            ->with()
            ->willReturn($productAbstract);

        $this->productAttributeFacade->expects($this->once())
            ->method('getProductAbstractAttributeValues')
            ->with($productAbstract->getIdProductAbstract())
            ->willReturn($oldAttributes);

        $this->translatorFacade->expects($this->once())
            ->method('trans')
            ->with('The attribute %attribute% already exists. Please define another one', ['%attribute%' => 'brand'])
            ->willReturn('translated message');

        $this->form->expects($this->once())
            ->method('getParent')
            ->with()
            ->willReturn($this->parentForm);

        // Act
        $this->setObject($this->form);
        $this->validator->validate($newAttributes, $constraint);

        // Assert
        $violations = $this->context->getViolations();

        $this->assertCount(1, $violations);
        $this->assertSame($violations->get(0)->getMessage(), 'translated message');
        $this->assertSame($violations->get(0)->getParameters(), ['attributesRowNumber' => 'invalid case']);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractAttributeUniqueCombinationConstraintValidator
     */
    protected function createValidator(): ConstraintValidator
    {
        return new ProductAbstractAttributeUniqueCombinationConstraintValidator();
    }
}

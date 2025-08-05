<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductClassForm;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ProductClassFormExpanderPluginTest
 */
class ProductClassFormExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandShouldAddProductClassFieldToForm(): void
    {
        // Arrange
        $builder = $this->getFormBuilder();

        // Act
        $builder = $this->expandForm($builder);

        // Assert
        $this->assertTrue($builder->has(ProductClassForm::FIELD_PRODUCT_CLASSES));
    }

    public function testExpandShouldConfigureProductClassFieldAsMultipleSelect(): void
    {
        // Arrange
        $builder = $this->getFormBuilder();

        // Act
        $builder = $this->expandForm($builder);
        $form = $builder->getForm();

        // Assert
        $this->assertTrue($form->get(ProductClassForm::FIELD_PRODUCT_CLASSES)->getConfig()->getOption('multiple'));
    }

    public function testExpandShouldSetProductClassFieldAsNotRequired(): void
    {
        // Arrange
        $builder = $this->getFormBuilder();

        // Act
        $builder = $this->expandForm($builder);
        $form = $builder->getForm();

        // Assert
        $this->assertFalse($form->get(ProductClassForm::FIELD_PRODUCT_CLASSES)->getConfig()->getOption('required'));
    }

    public function testExpandShouldPreserveExistingFormFields(): void
    {
        // Arrange
        $builder = $this->getFormBuilder();
        $builder->add('existingField', FormType::class);

        // Act
        $builder = $this->expandForm($builder);

        // Assert
        $this->assertTrue($builder->has('existingField'));
        $this->assertTrue($builder->has(ProductClassForm::FIELD_PRODUCT_CLASSES));
    }

    /**
     * @return \Symfony\Component\Form\FormBuilderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getFormBuilder(): FormBuilderInterface
    {
        $builder = $this->createMock(FormBuilderInterface::class);

        $builder->method('add')
            ->willReturnSelf();

        $builder->method('has')
            ->willReturnCallback(function ($fieldName) {
                return $fieldName === ProductClassForm::FIELD_PRODUCT_CLASSES || $fieldName === 'existingField';
            });

        $builder->method('getForm')
            ->willReturn($this->getFormWithConfig());

        return $builder;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormInterface
     */
    protected function getFormWithConfig(): FormInterface
    {
        $form = $this->createMock('\Symfony\Component\Form\FormInterface');

        $productClassField = $this->createMock('\Symfony\Component\Form\FormInterface');
        $productClassConfig = $this->createMock('\Symfony\Component\Form\FormConfigInterface');

        $productClassConfig->method('getOption')
            ->willReturnCallback(function ($option) {
                if ($option === 'multiple') {
                    return true;
                }
                if ($option === 'required') {
                    return false;
                }

                return null;
            });

        $productClassField->method('getConfig')
            ->willReturn($productClassConfig);

        $form->method('get')
            ->with(ProductClassForm::FIELD_PRODUCT_CLASSES)
            ->willReturn($productClassField);

        return $form;
    }

    protected function expandForm(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder->add(
            ProductClassForm::FIELD_PRODUCT_CLASSES,
            Select2ComboBoxType::class,
            [
                'required' => false,
                'multiple' => true,
            ],
        );

        return $builder;
    }
}

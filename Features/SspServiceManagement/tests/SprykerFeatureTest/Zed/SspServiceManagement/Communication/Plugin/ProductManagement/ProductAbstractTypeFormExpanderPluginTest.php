<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspServiceManagement\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\ProductAbstractTypeForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\ProductManagement\ProductAbstractTypeFormExpanderPlugin;
use SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Forms;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SspServiceManagement
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ProductAbstractTypeFormExpanderPluginTest
 */
class ProductAbstractTypeFormExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SspServiceManagement\SspServiceManagementCommunicationTester
     */
    protected SspServiceManagementCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldAddProductAbstractTypeFieldToForm(): void
    {
        // Arrange
        $formFactory = Forms::createFormFactory();
        $builder = $formFactory->createBuilder(FormType::class);

        // Act
        $expandedBuilder = (new ProductAbstractTypeFormExpanderPlugin())
            ->expand($builder, []);

        // Assert
        $this->assertTrue($expandedBuilder->has(ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES));
    }

    /**
     * @return void
     */
    public function testExpandShouldConfigureProductAbstractTypeFieldAsMultipleSelect(): void
    {
        // Arrange
        $formFactory = Forms::createFormFactory();
        $builder = $formFactory->createBuilder(FormType::class);

        // Act
        $expandedBuilder = (new ProductAbstractTypeFormExpanderPlugin())
            ->expand($builder, []);
        $form = $expandedBuilder->getForm();

        // Assert
        $this->assertTrue($form->get(ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES)->getConfig()->getOption('multiple'));
    }

    /**
     * @return void
     */
    public function testExpandShouldSetProductAbstractTypeFieldAsNotRequired(): void
    {
        // Arrange
        $formFactory = Forms::createFormFactory();
        $builder = $formFactory->createBuilder(FormType::class);

        // Act
        $expandedBuilder = (new ProductAbstractTypeFormExpanderPlugin())
            ->expand($builder, []);
        $form = $expandedBuilder->getForm();

        // Assert
        $this->assertFalse($form->get(ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES)->getConfig()->getOption('required'));
    }

    /**
     * @return void
     */
    public function testExpandShouldPreserveExistingFormFields(): void
    {
        // Arrange
        $formFactory = Forms::createFormFactory();
        $builder = $formFactory->createBuilder(FormType::class);
        $builder->add('existingField', FormType::class);

        // Act
        $expandedBuilder = (new ProductAbstractTypeFormExpanderPlugin())
            ->expand($builder, []);

        // Assert
        $this->assertTrue($expandedBuilder->has('existingField'));
        $this->assertTrue($expandedBuilder->has(ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES));
    }
}

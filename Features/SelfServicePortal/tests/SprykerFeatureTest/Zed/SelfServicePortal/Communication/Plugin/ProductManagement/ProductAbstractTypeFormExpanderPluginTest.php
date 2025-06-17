<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement\ProductAbstractTypeFormExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductAbstractTypeForm;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Forms;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ProductAbstractTypeFormExpanderPluginTest
 */
class ProductAbstractTypeFormExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

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

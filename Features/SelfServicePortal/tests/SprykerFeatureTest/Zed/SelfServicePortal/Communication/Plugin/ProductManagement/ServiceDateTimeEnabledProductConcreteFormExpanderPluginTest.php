<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement\ServiceDateTimeEnabledProductConcreteFormExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ServiceDateTimeEnabledProductConcreteForm;
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
 * @group ServiceDateTimeEnabledProductConcreteFormExpanderPluginTest
 */
class ServiceDateTimeEnabledProductConcreteFormExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldAddServiceDateTimeEnabledFieldToForm(): void
    {
        // Arrange
        $formFactory = Forms::createFormFactory();
        $builder = $formFactory->createBuilder(FormType::class);

        // Act
        $expandedBuilder = (new ServiceDateTimeEnabledProductConcreteFormExpanderPlugin())
            ->expand($builder, []);

        // Assert
        $this->assertTrue($expandedBuilder->has(ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED));
    }

    /**
     * @return void
     */
    public function testExpandShouldConfigureServiceDateTimeEnabledFieldAsNotRequired(): void
    {
        // Arrange
        $formFactory = Forms::createFormFactory();
        $builder = $formFactory->createBuilder(FormType::class);

        // Act
        $expandedBuilder = (new ServiceDateTimeEnabledProductConcreteFormExpanderPlugin())
            ->expand($builder, []);
        $form = $expandedBuilder->getForm();

        // Assert
        $this->assertFalse($form->get(ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED)->getConfig()->getOption('required'));
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
        $expandedBuilder = (new ServiceDateTimeEnabledProductConcreteFormExpanderPlugin())
            ->expand($builder, []);

        // Assert
        $this->assertTrue($expandedBuilder->has('existingField'));
        $this->assertTrue($expandedBuilder->has(ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED));
    }
}

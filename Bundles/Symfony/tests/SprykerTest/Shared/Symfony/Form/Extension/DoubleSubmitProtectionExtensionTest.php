<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Symfony\Form\Extension;

use Codeception\Test\Unit;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Symfony
 * @group Form
 * @group Extension
 * @group DoubleSubmitProtectionExtensionTest
 * Add your own group annotations below this line
 */
class DoubleSubmitProtectionExtensionTest extends Unit
{
    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\Type\DoubleSubmitFormType
     */
    protected $doubleSubmitFormType;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface
     */
    protected $generator;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolverInterface
     */
    protected $optionResolver;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->generator = $this->getMockBuilder(TokenGeneratorInterface::class)->setMethods(['checkTokenEquals', 'generateToken'])->getMock();
        $this->storage = $this->getMockBuilder(StorageInterface::class)->setMethods(['getToken', 'setToken', 'deleteToken', 'checkTokenEquals'])->getMock();
        $this->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();

        $this->formFactory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getFormExtensions())
            ->getFormFactory();
    }

    /**
     * @return void
     */
    public function testFinishViewIgnoredForNotFormRoot()
    {
        $view = $this->formFactory
            ->createNamedBuilder('root', FormType::class)
            ->add(
                $this->formFactory->createNamedBuilder(
                    'form',
                    FormType::class,
                    null,
                    ['token_field_name' => '_requestToken']
                )
            )
            ->getForm()
            ->get('form')
            ->createView();

        $this->assertFalse(isset($view['_requestToken']));
    }

    /**
     * @return void
     */
    public function testFinishFormViewSuccess()
    {
        $expectedToken = 'TOKEN';
        $this->generator->expects($this->once())
            ->method('generateToken')
            ->willReturn($expectedToken);

        $view = $this->formFactory
            ->createNamed('FORM_NAME', FormType::class, null, ['token_field_name' => '_requestToken'])
            ->createView();

        $this->assertEquals($expectedToken, $view['_requestToken']->vars['value']);
    }

    /**
     * @dataProvider booleanDataProvider
     *
     * @param bool $valid
     *
     * @return void
     */
    public function testValidateTokenOnSubmit($valid)
    {
        $expectedToken = 'TOKEN';

        $this->storage->expects($this->once())
            ->method('getToken')
            ->willReturn($expectedToken);

        $this->generator->expects($this->once())
            ->method('checkTokenEquals')
            ->with($expectedToken, $expectedToken)
            ->will($this->returnValue($valid));

        $form = $this->formFactory
            ->createBuilder(FormType::class, null, ['token_field_name' => '_requestToken'])
            ->add('child', TextType::class)
            ->getForm();

        $form->submit(['child' => 'foobar', '_requestToken' => $expectedToken]);

        // Remove token from data
        $this->assertSame(['child' => 'foobar'], $form->getData());

        // Validate accordingly
        $this->assertSame($valid, $form->isValid());
    }

    /**
     * @return array
     */
    protected function getFormExtensions()
    {
        return [
            new DoubleSubmitProtectionExtension($this->generator, $this->storage, $this->translator),
        ];
    }

    /**
     * @return array
     */
    public function booleanDataProvider()
    {
        return [
            [true],
            [false],
        ];
    }
}

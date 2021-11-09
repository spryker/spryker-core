<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\Type;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class ImageType extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_IMAGE_WIDTH = 'image_width';

    /**
     * @var string
     */
    public const OPTION_IMAGE_HEIGHT = 'image_height';

    /**
     * @var string
     */
    public const OPTION_IMAGE_TITLE = 'image_title';

    /**
     * @var string
     */
    public const OPTION_IMAGE_URL = 'image_url';

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars[static::OPTION_IMAGE_WIDTH] = $options[static::OPTION_IMAGE_WIDTH];
        $view->vars[static::OPTION_IMAGE_HEIGHT] = $options[static::OPTION_IMAGE_HEIGHT];
        $view->vars[static::OPTION_IMAGE_TITLE] = $options[static::OPTION_IMAGE_TITLE];
        $view->vars[static::OPTION_IMAGE_URL] = $options[static::OPTION_IMAGE_URL];
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            static::OPTION_IMAGE_WIDTH => null,
            static::OPTION_IMAGE_HEIGHT => null,
            static::OPTION_IMAGE_TITLE => null,
            static::OPTION_IMAGE_URL => null,
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return FormType::class;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class MimeTypeSettingsForm extends AbstractType
{
    const FIELD_MIME_TYPES = 'mimeTypes';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_MIME_TYPES, HiddenType::class);
    }
}

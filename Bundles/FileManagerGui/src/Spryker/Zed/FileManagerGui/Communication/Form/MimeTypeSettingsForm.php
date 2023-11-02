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
 * @method \Spryker\Zed\FileManagerGui\FileManagerGuiConfig getConfig()
 */
class MimeTypeSettingsForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_MIME_TYPES = 'mimeTypes';

    /**
     * @var string
     */
    public const FORM_DATA_KEY_ID_MIME_TYPE = 'idMimeType';

    /**
     * @var string
     */
    public const FORM_DATA_KEY_IS_ALLOWED = 'isAllowed';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_MIME_TYPES, HiddenType::class);
    }
}

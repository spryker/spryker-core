<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AttributeInputManager
{
    /**
     * @var string
     */
    protected const INPUT_TYPE_TEXT = 'text';

    /**
     * @var string
     */
    protected const INPUT_TYPE_TEXTAREA = 'textarea';

    /**
     * @var string
     */
    protected const INPUT_TYPE_SELECT2 = 'select2';

    /**
     * @var int
     */
    protected const VALUE_LENGTH_LIMIT = 255;

    /**
     * @var array<string, string>
     */
    protected const INPUT_TYPE_TO_SYMFONY_INPUT_TYPE_MAP = [
        self::INPUT_TYPE_TEXT => TextType::class,
        self::INPUT_TYPE_TEXTAREA => TextareaType::class,
        self::INPUT_TYPE_SELECT2 => Select2ComboBoxType::class,
    ];

    /**
     * @param string $inputType
     * @param string|null $value
     *
     * @return string
     */
    public function getSymfonyInputType(string $inputType, ?string $value = null): string
    {
        if (mb_strlen($value) > static::VALUE_LENGTH_LIMIT) {
            return TextareaType::class;
        }

        return static::INPUT_TYPE_TO_SYMFONY_INPUT_TYPE_MAP[$inputType] ?? TextType::class;
    }
}

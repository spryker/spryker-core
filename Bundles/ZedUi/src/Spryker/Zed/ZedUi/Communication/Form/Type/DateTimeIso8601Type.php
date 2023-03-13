<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication\Form\Type;

use DateTime;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ZedUi\Communication\ZedUiCommunicationFactory getFactory()
 */
class DateTimeIso8601Type extends DateTimeType
{
    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected const OPTION_KEY_WIDGET = 'widget';

    /**
     * @var string
     */
    protected const OPTION_VALUE_SINGLE_TEXT = 'single_text';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        if ($options[static::OPTION_KEY_WIDGET] === static::OPTION_VALUE_SINGLE_TEXT) {
            $builder->resetViewTransformers();
            $builder->addViewTransformer($this->createDateTimeViewTransformer());
        }

        $builder->addModelTransformer($this->createDateTimeModelTransformer());
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            $this->getTransformStringToDateTimeCallback(),
            $this->getTransformDateTimeToStringCallback(),
        );
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createDateTimeViewTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            $this->getTransformDateTimeToStringCallback(DATE_ATOM),
            $this->getTransformStringToDateTimeCallback(),
        );
    }

    /**
     * @return callable
     */
    protected function getTransformStringToDateTimeCallback(): callable
    {
        return function ($value) {
            if ($value !== null && $value !== '') {
                return new DateTime($value);
            }

            return null;
        };
    }

    /**
     * @param string $format
     *
     * @return callable
     */
    protected function getTransformDateTimeToStringCallback(string $format = self::DATE_TIME_FORMAT): callable
    {
        return function ($value) use ($format) {
            if ($value instanceof DateTime) {
                $value = $value->format($format);
            }

            return $value;
        };
    }
}

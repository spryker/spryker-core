<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer;

use DateTime;
use Exception;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ValidFromDataTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    protected const DATETIME_FORMAT = 'Y-m-d H:i:s.u';

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform($value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $dateTime = DateTime::createFromFormat(static::DATETIME_FORMAT, $value);

            if ($dateTime === false) {
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $value);
            }

            if ($dateTime === false) {
                $dateTime = DateTime::createFromFormat(static::DATE_FORMAT, $value);
            }

            if ($dateTime !== false) {
                return $dateTime;
            }
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @return mixed
     */
    public function reverseTransform($value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof DateTime) {
            return $value;
        }

        try {
            $dateTime = DateTime::createFromFormat(static::DATE_FORMAT, $value);

            if ($dateTime === false) {
                throw new TransformationFailedException(sprintf(
                    'The date "%s" is not a valid date. It should be in the format "%s".',
                    $value,
                    static::DATE_FORMAT,
                ));
            }

            $dateTime->setTime(0, 0, 0);

            return $dateTime;
        } catch (Exception $e) {
            throw new TransformationFailedException(sprintf(
                'The date "%s" is not a valid date. It should be in the format "%s".',
                $value,
                static::DATE_FORMAT,
            ), 0, $e);
        }
    }
}

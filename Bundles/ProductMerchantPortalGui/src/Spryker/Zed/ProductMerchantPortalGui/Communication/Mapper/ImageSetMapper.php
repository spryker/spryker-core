<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;

class ImageSetMapper implements ImageSetMapperInterface
{
    protected const FIELD_IMAGE_SMALL = 'externalUrlSmall';
    protected const FIELD_IMAGE_LARGE = 'externalUrlLarge';
    protected const FIELD_SORT_ORDER = 'sortOrder';
    protected const FIELD_NAME = 'name';
    protected const FIELD_PRODUCT_IMAGES = 'productImages';

    protected const IMAGE_SETS_FORM_FIELD = 'imageSets';

    protected const FORM_VALIDATION_IMAGE_SET_MAP = [
        self::FIELD_NAME => 'name',
        self::FIELD_PRODUCT_IMAGES => 'images',
    ];

    protected const FORM_VALIDATION_IMAGE_MAP = [
        self::FIELD_IMAGE_SMALL => 'srcSmall',
        self::FIELD_IMAGE_LARGE => 'srcLarge',
        self::FIELD_SORT_ORDER => 'order',
    ];

    /**
     * @param \Symfony\Component\Form\FormErrorIterator $errors
     *
     * @return string[][]
     */
    public function mapErrorsToImageSetValidationData(FormErrorIterator $errors): array
    {
        $result = [];

        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($errors as $error) {
            $propertyPath = $this->getPropertyPath($error);

            if (!$propertyPath) {
                continue;
            }

            $fields = explode('.', $propertyPath);

            if (count($fields) === 2) {
                $result = $this->mapErrorMessageToImageSetData($result, $fields, $error->getMessage());
            } elseif (count($fields) === 4) {
                $result = $this->mapErrorMessageToImageSetImagesData($result, $fields, $error->getMessage());
            }
        }

        return $this->fillNotExistingNumericArrayElementsWithEmptyArray($result);
    }

    /**
     * @param array $source
     *
     * @return array
     */
    protected function fillNotExistingNumericArrayElementsWithEmptyArray(array $source): array
    {
        if (!$source) {
            return $source;
        }

        $keys = array_keys($source);
        $max = max($keys);

        for ($index = 0; $index < $max; $index++) {
            if (!isset($source[$index])) {
                $source[$index] = [];
            }
        }
        ksort($source);

        return $source;
    }

    /**
     * @param \Symfony\Component\Form\FormError $error
     *
     * @return string|null
     */
    protected function getPropertyPath(FormError $error): ?string
    {
        $propertyPath = $error->getCause()->getPropertyPath();
        $position = strpos($propertyPath, static::IMAGE_SETS_FORM_FIELD);

        if ($position === false) {
            return null;
        }

        $propertyPath = substr($propertyPath, $position + strlen(static::IMAGE_SETS_FORM_FIELD) + 2);
        $propertyPath = (string)str_replace(['children', '[', ']'], '', $propertyPath);
        $propertyPath = (string)preg_replace('/\.data$/', '', $propertyPath);

        return $propertyPath;
    }

    /**
     * @param array $imageSetData
     * @param array $fields
     * @param string $errorMessage
     *
     * @return array
     */
    protected function mapErrorMessageToImageSetData(array $imageSetData, array $fields, string $errorMessage): array
    {
        [$imageSetIndex, $imageSetField] = $fields;

        $imageSetField = static::FORM_VALIDATION_IMAGE_SET_MAP[$imageSetField] ?? $imageSetField;
        $imageSetData[$imageSetIndex][$imageSetField] = $errorMessage;

        return $imageSetData;
    }

    /**
     * @param array $imageSetData
     * @param array $fields
     * @param string $errorMessage
     *
     * @return array
     */
    protected function mapErrorMessageToImageSetImagesData(array $imageSetData, array $fields, string $errorMessage): array
    {
        [$imageSetIndex, $imageSetField, $productImageIndex, $productImageField] = $fields;

        $imageSetField = static::FORM_VALIDATION_IMAGE_SET_MAP[$imageSetField] ?? $imageSetField;
        $productImageField = static::FORM_VALIDATION_IMAGE_MAP[$productImageField] ?? $productImageField;

        $imageSetData[$imageSetIndex][$imageSetField][$productImageIndex][$productImageField] = $errorMessage;

        return $imageSetData;
    }
}

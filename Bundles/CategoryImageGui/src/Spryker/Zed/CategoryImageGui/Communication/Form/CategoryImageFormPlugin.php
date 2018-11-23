<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form;

use ArrayObject;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryFormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CategoryImageGui\Communication\CategoryImageGuiCommunicationFactory getFactory()
 */
class CategoryImageFormPlugin extends AbstractPlugin implements CategoryFormPluginInterface
{
    public const FIELD_IMAGE_SETS = 'imageSets';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_IMAGE_SETS, ImageSetCollectionForm::class);
        $builder->get(static::FIELD_IMAGE_SETS)->addModelTransformer(new CallbackTransformer(
            $this->buildFormImageSetCollection(),
            $this->buildCategoryImageSetCollection()
        ));
    }

    /**
     * @return callable
     */
    private function buildFormImageSetCollection(): callable
    {
        return function (ArrayObject $imageSetCollection): array {
            return $this->getFactory()
                ->createImageSetLocalizer()
                ->buildLocalizedArrayFromImageSetCollection(
                    $imageSetCollection->getArrayCopy()
                );
        };
    }

    /**
     * @return callable
     */
    private function buildCategoryImageSetCollection(): callable
    {
        return function (array $localizedImageSetTransferArray): ArrayObject {
            $categoryImageSetCollection = $this->getFactory()
                ->createImageSetLocalizer()
                ->buildCategoryImageSetCollectionFromLocalizedArray($localizedImageSetTransferArray);

            return new ArrayObject($categoryImageSetCollection);
        };
    }
}

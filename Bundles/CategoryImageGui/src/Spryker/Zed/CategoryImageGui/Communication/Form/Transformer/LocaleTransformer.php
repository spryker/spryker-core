<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form\Transformer;

use Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface;
use Symfony\Component\Form\DataTransformerInterface;

class LocaleTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CategoryImageGui\Dependency\Facade\CategoryImageGuiToLocaleInterface $localeFacade
     */
    public function __construct(CategoryImageGuiToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $value The value in the original representation
     *
     * @return int|null The value in the transformed representation
     */
    public function transform($value)
    {
        if (!$value) {
            return null;
        }

        return $value->getIdLocale();
    }

    /**
     * {@inheritdoc}
     *
     * @param int|null $value The value in the transformed representation
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null The value in the original representation
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        return $this->localeFacade->getLocaleById($value);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreContextGui\Communication\Form\DataProvider\StoreContextFormDataProvider;
use Spryker\Zed\StoreContextGui\Communication\Form\StoreContextCollectionForm;
use Symfony\Component\Form\FormBuilderInterface;

class StoreContextFormExpander implements StoreContextFormExpanderInterface
{
    /**
     * @var string
     */
    protected const FIELD_APPLICATION_CONTEXT_COLLECTION = 'applicationContextCollection';

    /**
     * @var \Spryker\Zed\StoreContextGui\Communication\Form\DataProvider\StoreContextFormDataProvider
     */
    protected StoreContextFormDataProvider $storeContextFormDataProvider;

    /**
     * @param \Spryker\Zed\StoreContextGui\Communication\Form\DataProvider\StoreContextFormDataProvider $storeContextFormDataProvider
     */
    public function __construct(
        StoreContextFormDataProvider $storeContextFormDataProvider
    ) {
        $this->storeContextFormDataProvider = $storeContextFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, StoreTransfer $storeTransfer): FormBuilderInterface
    {
        $builder->add(
            static::FIELD_APPLICATION_CONTEXT_COLLECTION,
            StoreContextCollectionForm::class,
            $this->getOptions(),
        );

        return $builder;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return array_merge(
            $this->storeContextFormDataProvider->getOptions(),
            ['label' => false],
        );
    }
}

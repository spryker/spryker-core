<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\EventListener;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryGui\Communication\Finder\CategoryStoreWithStateFinderInterface;
use Spryker\Zed\CategoryGui\Communication\Form\CategoryType;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CategoryStoreRelationFieldEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Spryker\Zed\CategoryGui\Communication\Finder\CategoryStoreWithStateFinderInterface
     */
    protected $categoryStoreWithStateFinder;

    /**
     * @var \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected $storeRelationFormTypePlugin;

    /**
     * @var string
     */
    protected const HELP_TEXT_ASSIGN_PARENT_FIRST = 'If a store is not selectable, please make sure that the parent category is assigned to it first.';

    /**
     * @param \Spryker\Zed\CategoryGui\Communication\Finder\CategoryStoreWithStateFinderInterface $categoryStoreWithStateFinder
     * @param \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface $storeRelationFormTypePlugin
     */
    public function __construct(
        CategoryStoreWithStateFinderInterface $categoryStoreWithStateFinder,
        FormTypeInterface $storeRelationFormTypePlugin
    ) {
        $this->categoryStoreWithStateFinder = $categoryStoreWithStateFinder;
        $this->storeRelationFormTypePlugin = $storeRelationFormTypePlugin;
    }

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function preSetData(FormEvent $event): void
    {
        $idCategoryNode = $this->extractIdParentCategoryNodeFromEvent($event);
        if ($idCategoryNode === null) {
            return;
        }

        $form = $event->getForm();

        $options = $form->get(CategoryType::FIELD_STORE_RELATION)->getConfig()->getOptions();
        $options[CategoryType::OPTION_INACTIVE_CHOICES] = $this->categoryStoreWithStateFinder
            ->getInactiveStoreIdsByIdCategoryNode($idCategoryNode);

        if ($options[CategoryType::OPTION_INACTIVE_CHOICES]) {
            $options[CategoryType::OPTION_HELP] = $this->getHelpMessageForStoreRelationSelector();
        }

        $form->add(
            CategoryType::FIELD_STORE_RELATION,
            $this->storeRelationFormTypePlugin->getType(),
            $options,
        );
    }

    /**
     * @return string
     */
    protected function getHelpMessageForStoreRelationSelector(): string
    {
        return static::HELP_TEXT_ASSIGN_PARENT_FIRST;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return int|null
     */
    protected function extractIdParentCategoryNodeFromEvent(FormEvent $event): ?int
    {
        $categoryTransfer = $event->getData();
        if (!($categoryTransfer instanceof CategoryTransfer)) {
            return null;
        }

        $categoryNodeParentTransfer = $categoryTransfer->getParentCategoryNode();
        if ($categoryNodeParentTransfer !== null) {
            return $categoryNodeParentTransfer->getIdCategoryNode();
        }

        return null;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedUi\Communication\Twig;

use JsonSerializable;

class NavigationItem implements JsonSerializable
{
    /**
     * @var string|null
     */
    protected ?string $title;

    /**
     * @var string|null
     */
    protected ?string $url;

    /**
     * @var string|null
     */
    protected ?string $icon;

    /**
     * @var bool|null
     */
    protected ?bool $isActive;

    /**
     * @var array<array<string, mixed>>|null
     */
    protected ?array $subItems;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return void
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string|null $icon
     *
     * @return void
     */
    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return bool|null
     */
    public function getisActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool|null $isActive
     *
     * @return void
     */
    public function setIsActive(?bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return array<array<string, mixed>>|null
     */
    public function getSubItems(): ?array
    {
        return $this->subItems;
    }

    /**
     * @param array<array<string, mixed>>|null $subItems
     *
     * @return void
     */
    public function setSubItems(?array $subItems): void
    {
        $this->subItems = $subItems;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
            'icon' => $this->icon,
            'isActive' => $this->isActive,
            'subItems' => $this->subItems,
        ];
    }
}

<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Block\Listing\View\Product\Renderer;

interface RendererInterface
{
    /**
     * Render product for shopping list items
     *
     * @return string
     */
    public function render();
}
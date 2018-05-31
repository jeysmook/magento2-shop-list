<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Model;

interface ConfigProviderInterface
{
    /**
     * Retrieve assoc array of shopping list configuration
     *
     * @return array
     */
    public function getConfig();
}

<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Item;

use Magento\Framework\DataObject;
use Jeysmook\ShopList\Controller\AbstractItemAction;

class MassDelete extends AbstractItemAction
{
    public function execute()
    {
        $listId = (int)$this->getRequest()->getParam('list_id');
        if ($listId > 0) {
            $redirectObject = new DataObject(['path' => '*/listing/view', 'args' => ['id' => $listId]]);
        } else {
            $redirectObject = new DataObject(['path' => '*/', 'args' => []]);
        }

        $itemIds = $this->getRequest()->getParam(self::MASS_FIELD_NAME);
        if (!empty($itemIds) && is_array($itemIds)) {
            $rowDeletedCount = 0;
            $rowErrorCount = 0;

            $collection = $this->itemRepository->createCollection();
            $collection->addFieldToFilter('item_id', ['in' => $itemIds])
                ->addCustomerFilter($this->helper->getCustomer());

            foreach ($collection as $item) {
                try {
                    $this->itemRepository->delete($item);
                    $rowDeletedCount++;
                } catch (\Exception $e) {
                    $rowErrorCount++;
                }
            }

            if ($rowDeletedCount > 0) {
                $this->messageManager->addSuccessMessage(__('(%1) item(s) were deleted successfully.', $rowDeletedCount));
            }

            if ($rowErrorCount > 0) {
                $this->messageManager->addErrorMessage(__('(%1) item(s) have not been removed.', $rowErrorCount));
            }
        }

        return $this->_redirect($redirectObject->getPath(), $redirectObject->getArgs());
    }
}

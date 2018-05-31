<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Item;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Jeysmook\ShopList\Controller\AbstractItemAction;

class Delete extends AbstractItemAction
{
    public function execute()
    {
        try {
            $this->itemRepository->deleteById((int)$this->getRequest()->getParam('id', 0));
            $this->messageManager->addSuccessMessage(__('Item is deleted successfully.'));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage(__('Could not delete the item.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong, try again later.'));
        }

        $listId = (int)$this->getRequest()->getParam('list_id');
        if ($listId > 0) {
            return $this->_redirect('*/listing/view', ['id' => $listId]);
        } else {
            return $this->_redirect('*/');
        }
    }
}

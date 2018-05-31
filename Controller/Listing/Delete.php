<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Listing;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Jeysmook\ShopList\Controller\AbstractListingAction;

class Delete extends AbstractListingAction
{
    public function execute()
    {
        try {
            $this->listRepository->deleteById((int)$this->getRequest()->getParam('id', 0));
            $this->messageManager->addSuccessMessage(__('Shopping list is deleted successfully.'));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage(__('Could not delete the shopping list.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong, try again later.'));
        }

        return $this->_redirect('*/');
    }
}

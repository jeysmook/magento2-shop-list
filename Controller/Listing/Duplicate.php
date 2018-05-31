<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Listing;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Validator\Exception as ValidatorException;
use Jeysmook\ShopList\Controller\AbstractListingAction;

class Duplicate extends AbstractListingAction
{
    public function execute()
    {
        $list = $this->initList();

        try {
            $this->listRepository->duplicate($list);
            $this->messageManager->addSuccessMessage(__('Shopping list is duplicated successfully.'));
        } catch (CouldNotSaveException $e) {
            $this->messageManager->addErrorMessage(__('Could not save the duplicated shopping list.'));
        } catch (ValidatorException $e) {
            foreach ($e->getMessages() as $message) {
                $this->messageManager->addErrorMessage($message->getText());
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong, try again later.'));
        }

        return $this->_redirect('*/');
    }
}

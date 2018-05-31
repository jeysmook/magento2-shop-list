<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Listing;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Validator\Exception as ValidatorException;
use Jeysmook\ShopList\Api\Data\ListInterface;
use Jeysmook\ShopList\Controller\AbstractListingAction;

class SavePost extends AbstractListingAction
{
    public function execute()
    {
        /** @var ListInterface $list */
        $list = $this->initList();

        if ($list->getId() > 0) {
            $backActionName = $this->getRequest()->getParam('back_action');
            $path = !empty($backActionName) ? '*/*/' . $backActionName : '*/*/save';
            $redirectObject = new DataObject(['path' => $path, 'args' => ['id' => $list->getId()]]);
        } else {
            $redirectObject = new DataObject(['path' => '*/', 'args' => []]);
        }

        $listData = $this->getRequest()->getParam('list');
        if (!empty($listData)) {
            // add data to entity
            $list->addData($listData);
            $list->setCustomerId($this->helper->getCustomer()->getId());
            $list->setStoreId($this->helper->getCustomer()->getStoreId());

            try {
                $this->listRepository->save($list);
                $this->messageManager->addSuccessMessage(__('The shopping list is saved successfully.'));
                $redirectObject->setData('args', ['id' => $list->getId()]);
            } catch (CouldNotSaveException $e) {
                $this->messageManager->addErrorMessage(__('Could not save the shopping list.'));
            } catch (ValidatorException $e) {
                foreach ($e->getMessages() as $message) {
                    $this->messageManager->addErrorMessage($message->getText());
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong, try again later.'));
            }
        }

        return $this->_redirect($redirectObject->getPath(), $redirectObject->getArgs());
    }
}

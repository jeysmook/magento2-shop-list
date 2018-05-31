<?php
/**
 * Shopping list extension
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */
namespace Jeysmook\ShopList\Controller\Listing;

use Jeysmook\ShopList\Controller\AbstractListingAction;

class MassDelete extends AbstractListingAction
{
    public function execute()
    {
        $listIds = $this->getRequest()->getParam(self::MASS_FIELD_NAME);
        if (!empty($listIds) && is_array($listIds)) {
            $rowDeletedCount = 0;
            $rowErrorCount = 0;

            $collection = $this->listRepository->createCollection();
            $collection->addFieldToFilter('list_id', ['in' => $listIds])
                ->addStoreFilter($this->helper->getCustomer()->getStoreId())
                ->addCustomerFilter($this->helper->getCustomer()->getId());

            foreach ($collection as $list) {
                try {
                    $this->listRepository->delete($list);
                    $rowDeletedCount++;
                } catch (\Exception $e) {
                    $rowErrorCount++;
                }
            }

            if ($rowDeletedCount > 0) {
                $this->messageManager->addSuccessMessage(__('(%1) list(s) were deleted successfully.', $rowDeletedCount));
            }

            if ($rowErrorCount > 0) {
                $this->messageManager->addErrorMessage(__('(%1) list(s) have not been removed.', $rowErrorCount));
            }
        }

        return $this->_redirect('*/');
    }
}

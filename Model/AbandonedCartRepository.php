<?php

declare(strict_types=1);

namespace RCFerreira\AbandonedCart\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use RCFerreira\AbandonedCart\Api\AbandonedCartRepositoryInterface;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartInterface;
use RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart as ResourceModelAbandonedCart;
use RCFerreira\AbandonedCart\Model\ResourceModel\AbandonedCart\CollectionFactory;
use RCFerreira\AbandonedCart\Api\Data\AbandonedCartSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class AbandonedCartRepository implements AbandonedCartRepositoryInterface
{

    /**
     * @param ResourceModelAbandonedCart $resourceModelAbandonedCart
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param AbandonedCartSearchResultsInterfaceFactory $abandonedCartSearchResults
     */
    public function __construct(
        private ResourceModelAbandonedCart $resourceModelAbandonedCart,
        private CollectionFactory $collectionFactory,
        private CollectionProcessorInterface $collectionProcessor,
        private AbandonedCartSearchResultsInterfaceFactory $abandonedCartSearchResults
    ) {}

    /**
     * @param AbandonedCartInterface $abandonedCart
     * @return int
     * @throws CouldNotSaveException
     */
    public function save(AbandonedCartInterface $abandonedCart): int
    {
        try {
            $this->resourceModelAbandonedCart->save($abandonedCart);
            return (int) $abandonedCart->getId();
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Error to save notification'));
        }
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return array
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria): array
    {
        $items = [];
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->abandonedCartSearchResults->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        if (!empty($searchResults->getItems())) {
            foreach ($searchResults->getItems() as $item) {
                $items[] = $item->getData();
            }
        }

        return $items;
    }
}

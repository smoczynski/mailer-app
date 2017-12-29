<?php

namespace ApiBundle\Base\Model;

use ApiBundle\Base\Model\ResourceInterface;
use ApiBundle\Base\RestController;
use JMS\Serializer\Annotation as Serializer;

class CollectionResponse
{
    /**
     * Collection of items
     *
     * @var ResourceInterface[]
     * @Serializer\Type("array")
     * @Serializer\Groups({RestController::GROUP_OUTPUT})
     */
    private $items;

    /**
     * Total number of items
     *
     * @var int
     * @Serializer\Type("integer")
     * @Serializer\Groups({RestController::GROUP_OUTPUT})
     */
    private $totalItems;

    /**
     * @return ResourceInterface[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param ResourceInterface[] $items
     * @return CollectionResponse
     */
    public function setItems(array $items): CollectionResponse
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @param int $totalItems
     * @return CollectionResponse
     */
    public function setTotalItems(int $totalItems): CollectionResponse
    {
        $this->totalItems = $totalItems;

        return $this;
    }

}
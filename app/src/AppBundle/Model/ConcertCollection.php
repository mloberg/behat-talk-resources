<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation as JMS;

class ConcertCollection
{
    /**
     * @JMS\SerializedName("data")
     * @JMS\Type("array<AppBundle\Model\Concert>")
     */
    private $concerts;

    public function getConcerts()
    {
        return $this->concerts;
    }
}

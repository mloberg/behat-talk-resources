<?php

namespace AppBundle\Api\Service;

use Tebru\Retrofit\Annotation as Rest;

interface ConcertService
{
    /**
     * @Rest\GET("/concerts")
     * @Rest\Returns("AppBundle\Model\ConcertCollection")
     */
    public function getConcerts();
}

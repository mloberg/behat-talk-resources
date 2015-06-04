<?php

namespace AppBundle\Api\Service;

use Tebru\Retrofit\Annotation as Rest;

interface ConcertService
{
    /**
     * @Rest\GET("/concerts")
     */
    public function getConcerts();
}

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

    /**
     * @Rest\GET("/concerts/{id}")
     * @Rest\Returns("AppBundle\Model\Concert")
     */
    public function getConcert($id);
}

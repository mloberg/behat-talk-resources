<?php

namespace AppBundle\Api;

use Tebru\Retrofit\Adapter\RestAdapter as BaseRestAdapter;

class RestAdapter
{
    public static function get($baseUrl)
    {
        return BaseRestAdapter::builder()
            ->setBaseUrl($baseUrl)
            ->build();
    }
}

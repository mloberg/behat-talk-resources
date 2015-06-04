<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation as JMS;

class Concert
{
    /**
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @JMS\Type("string")
     */
    private $date;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }
}

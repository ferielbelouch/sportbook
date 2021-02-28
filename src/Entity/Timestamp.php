<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait Timestamp
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @return mixed 
     */

     public function createdAt()
     {
         return $this->createdAt;
     }

     /**
      * @ORM\PrePersist()
      */

    public function PrePersist()
    {
        $this->createdAt = new \DateTime();
    }

}
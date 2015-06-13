<?php

namespace Managlea\Tests\Models;


/**
 * @Entity
 * @Table(name="product")
 **/
class Product
{
    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") * */
    protected $name;

    /** @Column(name="date_of_birth", type="string", nullable=true) * */
    protected $dateOfBirth;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
}
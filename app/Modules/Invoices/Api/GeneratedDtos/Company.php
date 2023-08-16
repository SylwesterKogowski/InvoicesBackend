<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: Company.proto

namespace App\Modules\Invoices\Api\GeneratedDtos;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Ingenious.Invoice.Company</code>
 */
class Company extends \Google\Protobuf\Internal\Message
{
    /**
     * UUID of the company
     *
     * Generated from protobuf field <code>string id = 1;</code>
     */
    protected $id = '';
    /**
     * company name
     *
     * Generated from protobuf field <code>string name = 2;</code>
     */
    protected $name = '';
    /**
     * street address
     *
     * Generated from protobuf field <code>string street = 3;</code>
     */
    protected $street = '';
    /**
     * city
     *
     * Generated from protobuf field <code>string city = 4;</code>
     */
    protected $city = '';
    /**
     * zip code
     *
     * Generated from protobuf field <code>string zip = 5;</code>
     */
    protected $zip = '';
    /**
     * phone number
     *
     * Generated from protobuf field <code>string phone = 6;</code>
     */
    protected $phone = '';
    /**
     * email address
     *
     * Generated from protobuf field <code>string email = 7;</code>
     */
    protected $email = '';
    /**
     * unix timestamp in seconds of record creation time
     *
     * Generated from protobuf field <code>int64 createdAt = 8;</code>
     */
    protected $createdAt = 0;
    /**
     * unix timestamp in seconds of record update time
     *
     * Generated from protobuf field <code>int64 updatedAt = 9;</code>
     */
    protected $updatedAt = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $id
     *           UUID of the company
     *     @type string $name
     *           company name
     *     @type string $street
     *           street address
     *     @type string $city
     *           city
     *     @type string $zip
     *           zip code
     *     @type string $phone
     *           phone number
     *     @type string $email
     *           email address
     *     @type int|string $createdAt
     *           unix timestamp in seconds of record creation time
     *     @type int|string $updatedAt
     *           unix timestamp in seconds of record update time
     * }
     */
    public function __construct($data = NULL) {
        \App\Modules\Invoices\Api\GeneratedDtos\Metadata\Company::initOnce();
        parent::__construct($data);
    }

    /**
     * UUID of the company
     *
     * Generated from protobuf field <code>string id = 1;</code>
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * UUID of the company
     *
     * Generated from protobuf field <code>string id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkString($var, True);
        $this->id = $var;

        return $this;
    }

    /**
     * company name
     *
     * Generated from protobuf field <code>string name = 2;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * company name
     *
     * Generated from protobuf field <code>string name = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * street address
     *
     * Generated from protobuf field <code>string street = 3;</code>
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * street address
     *
     * Generated from protobuf field <code>string street = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setStreet($var)
    {
        GPBUtil::checkString($var, True);
        $this->street = $var;

        return $this;
    }

    /**
     * city
     *
     * Generated from protobuf field <code>string city = 4;</code>
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * city
     *
     * Generated from protobuf field <code>string city = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setCity($var)
    {
        GPBUtil::checkString($var, True);
        $this->city = $var;

        return $this;
    }

    /**
     * zip code
     *
     * Generated from protobuf field <code>string zip = 5;</code>
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * zip code
     *
     * Generated from protobuf field <code>string zip = 5;</code>
     * @param string $var
     * @return $this
     */
    public function setZip($var)
    {
        GPBUtil::checkString($var, True);
        $this->zip = $var;

        return $this;
    }

    /**
     * phone number
     *
     * Generated from protobuf field <code>string phone = 6;</code>
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * phone number
     *
     * Generated from protobuf field <code>string phone = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setPhone($var)
    {
        GPBUtil::checkString($var, True);
        $this->phone = $var;

        return $this;
    }

    /**
     * email address
     *
     * Generated from protobuf field <code>string email = 7;</code>
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * email address
     *
     * Generated from protobuf field <code>string email = 7;</code>
     * @param string $var
     * @return $this
     */
    public function setEmail($var)
    {
        GPBUtil::checkString($var, True);
        $this->email = $var;

        return $this;
    }

    /**
     * unix timestamp in seconds of record creation time
     *
     * Generated from protobuf field <code>int64 createdAt = 8;</code>
     * @return int|string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * unix timestamp in seconds of record creation time
     *
     * Generated from protobuf field <code>int64 createdAt = 8;</code>
     * @param int|string $var
     * @return $this
     */
    public function setCreatedAt($var)
    {
        GPBUtil::checkInt64($var);
        $this->createdAt = $var;

        return $this;
    }

    /**
     * unix timestamp in seconds of record update time
     *
     * Generated from protobuf field <code>int64 updatedAt = 9;</code>
     * @return int|string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * unix timestamp in seconds of record update time
     *
     * Generated from protobuf field <code>int64 updatedAt = 9;</code>
     * @param int|string $var
     * @return $this
     */
    public function setUpdatedAt($var)
    {
        GPBUtil::checkInt64($var);
        $this->updatedAt = $var;

        return $this;
    }

}

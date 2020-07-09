<?php

namespace seregazhuk\PinterestBot\Api\Forms;

class Registration extends Form
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $country = 'GB';

    /**
     * @var string
     */
    protected $age = '18';

    /**
     * @var string
     */
    protected $gender = 'male';

    /**
     * @var string
     */
    protected $site;

    /**
     * @param string $email
     * @param string $password
     * @param string $name
     */
    public function __construct($email, $password, $name)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    /**
     * @param mixed $email
     * @return Registration
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $password
     * @return Registration
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $name
     * @return Registration
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $country
     * @return Registration
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param int $age
     * @return Registration
     */
    public function setAge($age)
    {
        // Pinterest requires age to be a string
        $this->age = (string)$age;
        return $this;
    }

    /**
     * @param string $gender
     * @return Registration
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return Registration
     */
    public function setMaleGender()
    {
        return $this->setGender('male');
    }

    /**
     * @return Registration
     */
    public function setFemaleGender()
    {
        return $this->setGender('female');
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'first_name'    => $this->name,
            'last_name'     => '',
            'email'         => $this->email,
            'password'      => $this->password,
            'age'           => $this->age,
            //'birthday'        => '168830336',
            'country'       => $this->country,
            'locale'          => 'en-US',
            'container'     => 'home_page',
            'signupSource'  => 'homePage',
            'page'          => 'home',
            'recapToken'          => '03AGdBq24x6etOpijIjhkHp5lAll5ROgrCe_V0R01DtZP6WQTIAJ8HjKXu58oERJIFTw054Sh81Q3oCwInBXU4I59PBwm8XbPuDXwEcVbEjxEMSfQpSZzIIffV2lORv',
            'user_behavior_data' => '{}',
            'context' => '{}'
        ];
    }

    /**
     * @param string $site
     * @return Registration
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }
}

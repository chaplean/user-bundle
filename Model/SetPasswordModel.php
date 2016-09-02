<?php

namespace Chaplean\Bundle\UserBundle\Model;

/**
 * SetPasswordModel.php
 *
 * @package   Chaplean\Bundle\UserBundle\Model
 * @author    Matthias - Chaplean <matthias@chaplean.com>
 * @copyright 2014 - 2016 Chaplean (http://www.chaplean.com)
 * @since     4.0.0
 */
class SetPasswordModel
{
    /** @var string */
    private $password;

    /** @var string */
    private $token;

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return SetPasswordModel
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token.
     *
     * @param string $token
     *
     * @return SetPasswordModel
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
    
    
}

<?php
namespace Autoplan\ServiceBundle\Security;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserEncoder implements PasswordEncoderInterface
{

    /**
     * Encodes the raw password.
     *
     * @param string $raw  The password to encode
     * @param string $salt The salt
     *
     * @return string The encoded password
     */
    function encodePassword($raw, $salt)
    {
        return sha1($salt.$raw);
    }

    /**
     * Checks a raw password against an encoded password.
     *
     * @param string $encoded An encoded password
     * @param string $raw     A raw password
     * @param string $salt    The salt
     *
     * @return Boolean true if the password is valid, false otherwise
     */
    function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === $this->encodePassword($raw, $salt);
    }
}

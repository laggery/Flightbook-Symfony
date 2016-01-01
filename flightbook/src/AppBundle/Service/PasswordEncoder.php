<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordEncoder implements PasswordEncoderInterface {

    public function encodePassword($raw, $salt) {
        $md5pwd = md5($raw);
        $sha1 = sha1($md5pwd);
        return crypt($sha1, md5($salt));
    }

    public function isPasswordValid($encoded, $raw, $salt) {
        return $encoded === $this->encodePassword($raw, $salt);
    }
}

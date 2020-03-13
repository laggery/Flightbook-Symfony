<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Service;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * @author Elnur Abdurrakhimov <elnur@elnur.pro>
 * @author Terje Bråten <terje@braten.be>
 */
class BCryptPasswordEncoder extends BasePasswordEncoder implements PasswordEncoderInterface
{
    const MAX_PASSWORD_LENGTH = 72;

    private $cost;

    /**
     * @param int $cost The algorithmic cost that should be used
     *
     * @throws \RuntimeException         When no BCrypt encoder is available
     * @throws \InvalidArgumentException if cost is out of range
     */
    public function __construct($cost, $doctrine)
    {
        $cost = (int) $cost;
        if ($cost < 4 || $cost > 31) {
            throw new \InvalidArgumentException('Cost must be in the range of 4-31.');
        }

        $this->em = $doctrine;

        $this->cost = $cost;
    }

    /**
     * Encodes the raw password.
     *
     * It doesn't work with PHP versions lower than 5.3.7, since
     * the password compat library uses CRYPT_BLOWFISH hash type with
     * the "$2y$" salt prefix (which is not available in the early PHP versions).
     *
     * @see https://github.com/ircmaxell/password_compat/issues/10#issuecomment-11203833
     *
     * It is almost best to **not** pass a salt and let PHP generate one for you.
     *
     * @param string $raw  The password to encode
     * @param string $salt The salt
     *
     * @return string The encoded password
     *
     * @throws BadCredentialsException when the given password is too long
     *
     * @see http://lxr.php.net/xref/PHP_5_5/ext/standard/password.c#111
     */
    public function encodePassword($raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        $options = ['cost' => $this->cost];

        return str_replace("$2y$", "$2a$", password_hash($raw, PASSWORD_BCRYPT, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        if (!$this->isPasswordTooLong($raw) && password_verify($raw, $encoded)){
            return true;
        } else {
            $md5pwd = md5($raw);
            $sha1 = sha1($md5pwd);
            $cryptPwd = crypt($sha1, md5($salt));
            if ($encoded === $cryptPwd) {
                $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('salt' => $salt, 'password' => $encoded));
                if ($user) {
                    $user->setSalt(null);
                    $user->setPassword($this->encodePassword($raw, null));
                    $this->em->getManager()->persist($user);
                }
                return true;
            };

            return false;
        };
    }
}

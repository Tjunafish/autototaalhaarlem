<?php
/**
 * Created by JetBrains PhpStorm.
 * User: simonsabelis
 * Date: 6/27/13
 * Time: 3:55 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Autoplan\ServiceBundle\Security;


use Autoplan\DBBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider extends ContainerAware implements UserProviderInterface{

    /**
     * @var EntityManager $oEm
     */
    public $oEm;

    /**
     * @param Registry $oRegistry
     */
    public  function __construct(Container $oContainer) {
        $this->container = $oContainer;
        $this->oEm = $oContainer->get('doctrine')->getManager();
    }

   /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        /** @var User $oUser */
        $oUser = $this->oEm->getRepository('AutoplanDBBundle:User')->findOneBy(array(
            'email' => $username,
        ));
        if(null===$oUser) {
            throw new UsernameNotFoundException();
        } else {
            return $oUser;
        }
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->oEm->getRepository('AutoplanDBBundle:User')->findOneBy(array(
            'email' => $user->getUsername()
        ));
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class === 'Autoplan\DBBundle\Entity\User';
    }
}
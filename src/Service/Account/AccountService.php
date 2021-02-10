<?php
/**
 * Created by PhpStorm.
 * User: Marshall.D.Teach
 * Date: 26/03/2020
 * Time: 13:06
 */

namespace App\Service\Account;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccountService
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setUser($id)
    {
        $user = $this->session->get('user', []);
        $user['customer'] = $id;
        $this->session->set('user', $user);
    }

    public function getUser()
    {
        $user = $this->session->get('user', []);

        return (!empty($user['customer'])) ? $user['customer'] : null;
    }

    public function removeUser()
    {
        $user = $this->session->get('user', []);
        if(!empty($user['customer'])) { unset($user['customer']); }
        $this->session->set('user', $user);
    }

    public function setUserAdmin($id)
    {
        $user = $this->session->get('admin', []);
        $user['admin'] = $id;
        $this->session->set('admin', $user);
    }

    public function getUserAdmin()
    {
        $user = $this->session->get('admin', []);

        return (!empty($user['admin'])) ? $user['admin'] : null;
    }

    public function removeUserAdmin()
    {
        $user = $this->session->get('admin', []);

        if(!empty($user['admin'])) { unset($user['admin']); }

        $this->session->set('admin', $user);
    }
}
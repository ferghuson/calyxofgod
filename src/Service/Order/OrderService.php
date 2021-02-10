<?php
/**
 * Created by PhpStorm.
 * User: Marshall.D.Teach
 * Date: 01/04/2020
 * Time: 11:27
 */

namespace App\Service\Order;


use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{
    protected $session, $repository;

    public function __construct(SessionInterface $session, AddressRepository $repository)
    {
        $this->session = $session;
        $this->repository = $repository;
    }

    public function setAddress($id)
    {
        $order = $this->session->get('order', []);
        $order['address'] = $id;
        $this->session->set('order', $order);
    }

    public function getAddress()
    {
        $order = $this->session->get('order', []);

        if (!array_key_exists('address', $order)){
            return null;
        }

        return $this->repository->find($order['address']);
    }

    public function setPayment($method)
    {
        $order = $this->session->get('order', []);
        $order['payment'] = $method;
        $this->session->set('order', $order);
    }

    public function getPayment()
    {
        $order = $this->session->get('order', []);

        return $order['payment'];
    }
}
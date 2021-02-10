<?php
/**
 * Created by PhpStorm.
 * User: Marshall.D.Teach
 * Date: 30/03/2020
 * Time: 15:22
 */

namespace App\Service\Cart;


use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session ,$repository;

    public function __construct(SessionInterface $session, ProductRepository $repository)
    {
        $this->session = $session;
        $this->repository = $repository;
    }

    public function add($product, $quantity = 1)
    {
        $cart = $this->session->get('cart', []);

        $quantity = (int)$quantity;

        if(!empty($cart[$product])){
            $cart[$product] += $quantity;
        }else{
            $cart[$product] = $quantity;
        }

        $this->session->set('cart', $cart);
    }

    public function update($product, $newQuantity)
    {
        $cart = $this->session->get('cart', []);

        $cart[$product] = (int)$newQuantity;

        $this->session->set('cart', $cart);
    }

    public function remove($id)
    {
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id]))
        {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function clearCart()
    {
        $cart = $this->session->remove('cart');

        unset($cart);
    }

    public function getFullCart()
    {
        $cart = $this->session->get('cart', []);
        $cartData = [];

        foreach($cart as $id => $quantity){
            $cartData[] = [
                'product' => $this->repository->find($id),
                'quantity' => $quantity,
            ];
        }

        return $cartData;
    }

    public function getTotalQuantity()
    {
        $totalQuantity = 0;
        foreach ($this->getFullCart() as $item) {
            $totalQuantity += $item['quantity'];
        }

        return $totalQuantity;
    }

    public function getTotalPrice()
    {
        $totalPrice = 0;

        foreach ($this->getFullCart() as $item) {
            $totalPrice +=  $item['product']->getPrice() * $item['quantity'];
        }

        return $totalPrice;
    }

    public function getCartInfo()
    {
        return [
            'items'=>$this->getTotalQuantity(),
            'total'=>$this->getTotalPrice()
        ];
    }
}
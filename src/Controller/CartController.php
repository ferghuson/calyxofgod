<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(CartService $cart)
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFullCart(),
            'total' => $cart->getTotalPrice(),
            'items' => $cart->getTotalQuantity()
        ]);
    }

    /**
     * @Route("/cart/info", name="cart_info")
     */
    public function info(CartService $cartService)
    {
        return $this->json($cartService->getCartInfo(), 200);
    }

    /**
     * @Route("/cart/add/{product}/{quantity}", name="cart_add")
     */
    public function add($product, $quantity=1, CartService $cartService)
    {
        $cartService->add($product, $quantity);

        return $this->json($cartService->getCartInfo(), 200);
    }

    /**
     * @Route("/cart/remove/{product}", name="cart_remove")
     */
    public function remove($product, CartService $cartService)
    {
        $cartService->remove($product);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/cart/update/{product}/{newQuantity}", name="cart_update")
     */
    public function update($product, $newQuantity, CartService $cartService)
    {
        $cartService->update($product, $newQuantity);

        return $this->json($cartService->getCartInfo(), 200);
    }
}

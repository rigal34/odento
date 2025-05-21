<?php
namespace App\Service;


use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{

private RequestStack $requestStack;
private ArticleRepository $articleRepository;
private SessionInterface $session;

	public function __construct(RequestStack $requestStack, ArticleRepository $articleRepository, SessionInterface $session)
    {
      $this->requestStack = $requestStack;
      $this->articleRepository = $articleRepository;
      $this->session = $this->requestStack->getSession();  
    }

    public function getDetailedCart(): array
   {

     $cartRaw = $this->session->get('cart', []);
     $detailedCart = [];    
        $totalCartAmount = 0;   

        if (is_array)($cartRaw) {
            foreach ($cartRaw as $id => $quantity) {
                $id = (int)$id; // Convertir l'ID en entier
                $quanta
            }
        }





   }







}
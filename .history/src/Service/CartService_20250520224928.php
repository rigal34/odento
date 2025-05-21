<?php
namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use DeepCopy\f001\A;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartService
{

private RequestStack $requestStack;
private ArticleRepository $articleRepository;
private SessionInterface $session;

    public function __construct(RequestStack $requestStack, ArticleRepository $articleRepository)
    {
        $this->requestStack = $requestStack;
        $this->articleRepository = $articleRepository;
        $this->session = $requestStack->getSession();
    }

    /**
     * @return array
     */
    public function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    /**
     * @param int $id
     * @return void
     */
    public function addToCart(int $id): void
    {
        $cart = $this->getCart();

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }


	public function __construct()
    {
        
    }
}
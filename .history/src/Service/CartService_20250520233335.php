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








    
}
<?php
namespace App\Service;



class MailService
{


	public function __construct(RequestStack $requestStack, ArticleRepository $articleRepository)
    {
      $this->requestStack = $requestStack;
      $this->articleRepository = $articleRepository;
      $this->session = $this->requestStack->getSession();  
    }



   }







}
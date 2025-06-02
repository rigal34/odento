<?php

namespace App\Controller;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use App\Form\CheckoutType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    private CartService $cartService;
    private EntityManagerInterface $entityManager;

public function __construct(CartService $cartService, EntityManagerInterface $entityManager)
{
    $this->cartService = $cartService;
    $this->entityManager = $entityManager;

}


//MA METHODE PAR LE PANIER!!!!!!!!!!!!!!!!!!!!!!!!!
#[Route('/checkout', name: 'app_checkout', methods: ['GET', 'POST'])]
public function checkout(Request $request): Response
{
   
 

    if ($request->isMethod('POST')) {
       
    }

    $cartData = $this->cartService->getDetailedCart();

    if (empty($cartData['items'])) {
        $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer à la caisse pour le moment.');
        return $this->redirectToRoute('app_panier');
    }

    $form = $this->createForm(CheckoutType::class);
    
 
    $form->handleRequest($request);
 
   

    if ($form->isSubmitted()) {
        
        if ($form->isValid()) {
            $data = $form->getData();
           
            
           
           
           
            $session = $request->getSession();
            $session->set('checkout_data', $data);
            return $this->redirectToRoute('app_checkout_confirm'); 

        } else {
           
            $this->addFlash('error', 'Test: Formulaire soumis mais NON VALIDE.');
            
        }
    } else {
        
            
            $this->addFlash('error', 'Test: Problème de soumission du formulaire (POST mais non soumis).');
        
    }

    return $this->render('checkout/checkout.html.twig', [
        'controller_name' => 'Processus de Commande',
        'checkout_items' => $cartData['items'],
        'checkout_total_amount' => $cartData['total'],
        'checkout_form' => $form->createView(),
    ]);
}

//MA METHODE DE CONFIRMATION DE COMMANDE !!!!!!!!!!!!!!!!!!!!!!!!!!
#[Route('/checkout/confirmation', name: 'app_checkout_confirm', methods: ['GET'])]

public function confirmation(Request $request): Response
{
    $cartData = $this->cartService->getDetailedCart();

    $session = $request->getSession();
    $checkoutData = $session->get('checkout_data');
    if (empty($cartData['items']) || !$checkoutData) {
        $this->addFlash('warning', 'Impossible d\'afficher la confirmation de commande. Votre session de commande a peut-être expiré ou votre panier est vide.');
        return $this->redirectToRoute('app_panier');
    }

    return $this->render('checkout/confirmation.html.twig', [
        'controller_name' => 'Confirmation de Commande',
        'checkout_items' => $cartData['items'],
        'checkout_total_amount' => $cartData['total'],
        'checkout_data' => $checkoutData,
    ]);
}

#[Route('/checkout/place-order', name: 'app_checkout_place_order', methods: ['GET'])]

  public function placeOrder(Request $request): RedirectResponse
  {
    $session = $request->getSession();
    $checkoutData = $session->get('checkout_data');
    $cartData = $this->cartService->getDetailedCart();
    if (!$checkoutData || empty($cartData['items'])) {
        $this->addFlash('warning', 'Impossible de passer la commande. Votre session de commande a peut-être expiré ou votre panier est vide.');
        return $this->redirectToRoute('app_panier');
    }
  }

$order =new Order();

if ($this->getUser()) {

    $order->setUser($this->getUser());
}
$order->setCustomerFirstName($checkoutData['first_name'] ?? 'N/A');
$order->setCustomerLastName($checkoutData['last_name'] ?? 'N/A');
$order->setCustomerEmail($checkoutData['email'] ?? 'N/A');
$order->setCustomerPhone($checkoutData['phone'] ?? 'N/A');
$order->setShippingAddress($checkoutData['address'] ?? 'N/A');
$order->setShippingApartment($checkoutData['apartment'] ?? null);
$order->setShippingCity($checkoutData['city'] ?? 'N/A');
$order->setShippingZipCode($checkoutData['zip_code'] ?? 'N/A');
$order->setShippingState
}

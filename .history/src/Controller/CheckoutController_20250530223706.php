<?php

namespace App\Controller;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use App\Form\CheckoutType;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $cartData = $this->cartService->getDetailedCart();

        if (empty($cartData['items'])) {
            $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer à la caisse pour le moment.');
            return $this->redirectToRoute('app_panier');
        }

        $form = $this->createForm(CheckoutType::class); 
                                                       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $checkoutFormData = $form->getData(); 
        
        
            $order = new Order();

        
            if ($this->getUser()) {
                $order->setUser($this->getUser());
            }
           
            $order->setCustomerFirstName($checkoutFormData['firstName'] ?? 'Non fourni');
            $order->setCustomerLastName($checkoutFormData['lastName'] ?? 'Non fourni');
            $order->setCustomerEmail($checkoutFormData['email'] ?? 'Non fourni');
            $order->setCustomerPhone($checkoutFormData['phone'] ?? 'Non fourni');
            $order->setShippingAddress($checkoutFormData['address'] ?? 'Non fournie');
            $order->setShippingApartment($checkoutFormData['apartment'] ?? null);
            $order->setShippingCity($checkoutFormData['city'] ?? 'Non fournie');
            $order->setShippingZipCode($checkoutFormData['zipCode'] ?? 'Non fourni'); 
            $order->setShippingState($checkoutFormData['state'] ?? null);
            $order->setShippingCountry($checkoutFormData['country'] ?? 'Non fourni');
            
            $order->setTotalAmount($cartData['total']);
            $order->setStatus('Nouvelle commande'); 
            
            $this->entityManager->persist($order); 
            
            foreach ($cartData['items'] as $cartItem) {
                $orderItem = new OrderItem();
                
                $orderItem->setRelatedOrder($order); 
                $orderItem->setProductName($cartItem['product']->getTitle());
                $orderItem->setProductPrice($cartItem['product']->getPrice());
                $orderItem->setQuantity($cartItem['quantity']);
                $orderItem->setLineTotal($cartItem['lineTotal']); 
                
                

                $this->entityManager->persist($orderItem); 
                
               
            }

            
            $this->entityManager->flush();

           
            $session = $request->getSession();
            $session->remove('cart'); 
            $session->remove('checkout_data'); 

            
            $this->addFlash('success', 'Merci ! Votre commande #' . $order->getId() . ' a été enregistrée avec succès.');

           
            return $this->redirectToRoute('app_checkout_thank_you', ['orderId' => $order->getId()]);
            

        } elseif ($form->isSubmitted() && !$form->isValid()) {
            
            $this->addFlash('error', 'Le formulaire contient des erreurs. Veuillez vérifier les champs en rouge.');
        }

        return $this->render('checkout/checkout.html.twig', [
            'controller_name' => 'Processus de Commande',
            'checkout_items' => $cartData['items'],
            'checkout_total_amount' => $cartData['total'],
            'checkout_form' => $form->createView(),
        ]);
    }

  #[Route('/commande/merci/{orderId}', name: 'app_checkout_thank_you', methods: ['GET'])]
  public function thankYouPage(int $orderId, EntityManagerInterface $entityManager): Response
  {
    
    $order = $entityManager->getRepository(Order::class)->find($orderId);

    
    if (!$order) {
        $this->addFlash('warning', 'La commande que vous cherchez n\'a pas été trouvée.');
        return $this->redirectToRoute('app_home'); // Redirige vers l'accueil
    }
      dump('--- Débogage dans thankYouPage ---');
     dump('Objet Order complet:', $order);
          $orderItems = $order->getOrderItems(); // Récupère la collection d'OrderItems
    dump('Collection OrderItems:', $orderItems);
    dump('Nombre d\'OrderItems dans la commande: ' . count($orderItems));

  if (!$orderItems->isEmpty()) {
        foreach ($orderItems as $index => $orderItem) {
            dump('--- Détail OrderItem #' . $index . ' ---');
            dump('Objet OrderItem entier:', $orderItem);
            $productArticle = $orderItem->getProductArticle(); // Appelle le getter
            dump('Objet Article lié (via getProductArticle()):', $productArticle);
            if ($productArticle) {
                dump('Nom du fichier image de l\'Article lié:', $productArticle->getImage());
            } else {
                dump('Aucun objet Article n\'est lié à cet OrderItem via getProductArticle().');
            }
        }
    } else {
        dump('La collection orderItems est vide.');
    }
    dd('--- Fin du débogage dans thankYouPage. Vérifiez les dumps ci-dessus. ---');
    // ---- FIN DU BLOC DE DÉBOGAGE ----




    return $this->render('checkout/confirmation.html.twig', [
        'controller_name' => 'Merci pour votre Commande !',
        'order' => $order, 
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

  public function placeOrder(Request $request, MailService $mail_service): RedirectResponse
  {
   
    $session = $request->getSession();
    $checkoutData = $session->get('checkout_data');
    $cartData = $this->cartService->getDetailedCart();
    if (!$checkoutData || empty($cartData['items'])) {
        $this->addFlash('warning', 'Impossible de passer la commande. Votre session de commande a peut-être expiré ou votre panier est vide.');
        return $this->redirectToRoute('app_panier');
    }
  

  $order =new Order();

  if ($this->getUser()) {

    $order->setUser($this->getUser());
  }
  $order->setCustomerFirstName($checkoutData['firstName'] ?? 'N/A');
  $order->setCustomerLastName($checkoutData['lastName'] ?? 'N/A');
  $order->setCustomerEmail($checkoutData['email'] ?? 'N/A');
  $order->setCustomerPhone($checkoutData['phone'] ?? 'N/A');
  $order->setShippingAddress($checkoutData['address'] ?? 'N/A');
  $order->setShippingApartment($checkoutData['apartment'] ?? null);
  $order->setShippingCity($checkoutData['city'] ?? 'N/A');
  $order->setShippingState($checkoutData['state'] ?? null);
$order->setShippingZipCode($checkoutData['zip_code'] ?? 'N/A');
$order->setShippingCountry($checkoutData['country'] ?? 'N/A');
$order->setTotalAmount($cartData['total']);
$order->setStatus('Nouvelle Commande');
$this->entityManager->persist($order);

foreach ($cartData['items'] as $cartItem) {
    $orderItem = new OrderItem();
    $orderItem->setRelatedOrder($order);
    $orderItem->setProductName($cartItem['product']->getTitle());
    $orderItem->setProductPrice($cartItem['product']->getPrice());
    $orderItem->setQuantity($cartItem['quantity']);
    $orderItem->setLineTotal($cartItem['lineTotal']);
    // 👇👇👇 LIGNE ESSENTIELLE À AJOUTER OU À VÉRIFIER ICI 👇👇👇
    if ($cartItem['product'] instanceof \App\Entity\Article) { // Bonne pratique de vérifier le type
        $orderItem->setProductArticle($cartItem['product']); // On lie l'OrderItem à l'Article original
    }
    // 👆👆👆 FIN DE LA LIGNE ESSENTIELLE 👆👆👆
    $this->entityManager->persist($orderItem);

    $order->addOrderItem($orderItem);
    if (method_exists($order, 'addOrderItem')) { // Petite sécurité
        $order->addOrderItem($orderItem); 
}
$this->entityManager->flush();

$orderId = $order->getId(); 

$session->remove('cart');
$session->remove('checkout_data');
// Envoi d'un email de confirmation
$mail_service->sendEmail($checkoutData['email'], 
    'Confirmation de votre commande #' . $orderId,
    'Merci pour votre commande ! Voici les détails :<br>' .
    'Nom : ' . $checkoutData['firstName'] . ' ' . $checkoutData['lastName'] . '<br>' .
    'Email : ' . $checkoutData['email'] . '<br>' .
    'Téléphone : ' . $checkoutData['phone'] . '<br>' .
    'Adresse de livraison : ' . $checkoutData['address'] . ', ' .
    ($checkoutData['apartment'] ? $checkoutData['apartment'] . ', ' : '') .
    $checkoutData['city'] . ', ' .
    ($checkoutData['state'] ? $checkoutData['state'] . ', ' : '') .
    $checkoutData['zip_code'] . ', ' .
    $checkoutData['country'] . '<br>' .
    'Montant total : €' . number_format($cartData['total'], 2)
);
$this->addFlash('success', 'Merci ! Votre commande #' . $orderId . ' a été enregistrée avec succès. Vous pouvez consulter les détails ci-dessous.');

return $this->redirectToRoute('app_checkout_thank_you', ['orderId' => $orderId]);
}
}
}
<?php

namespace App\Controller;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use App\Form\CheckoutType;
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

        $form = $this->createForm(CheckoutType::class); // Pour l'instant, un formulaire non lié à une entité
                                                       // On pourrait passer un objet ou un tableau pour les données par défaut si besoin
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire d'adresse est soumis et valide
            $checkoutFormData = $form->getData(); // Récupère les données du formulaire (prénom, nom, adresse, etc.)

            // 1. Créer un nouvel objet Order
            $order = new Order();

            // 2. Remplir l'objet Order avec les données
            if ($this->getUser()) { // Si l'utilisateur est connecté
                $order->setUser($this->getUser());
            }
            // Assure-toi que les clés ici correspondent EXACTEMENT aux noms des champs dans CheckoutType.php
            $order->setCustomerFirstName($checkoutFormData['firstName'] ?? 'Non fourni');
            $order->setCustomerLastName($checkoutFormData['lastName'] ?? 'Non fourni');
            $order->setCustomerEmail($checkoutFormData['email'] ?? 'Non fourni');
            $order->setCustomerPhone($checkoutFormData['phone'] ?? 'Non fourni');
            $order->setShippingAddress($checkoutFormData['address'] ?? 'Non fournie');
            $order->setShippingApartment($checkoutFormData['apartment'] ?? null);
            $order->setShippingCity($checkoutFormData['city'] ?? 'Non fournie');
            $order->setShippingZipCode($checkoutFormData['zipCode'] ?? 'Non fourni'); // camelCase si c'est ce que tu as dans CheckoutType
            $order->setShippingState($checkoutFormData['state'] ?? null);
            $order->setShippingCountry($checkoutFormData['country'] ?? 'Non fourni');
            
            $order->setTotalAmount($cartData['total']);
            $order->setStatus('Nouvelle commande'); // Tu peux définir un statut initial
            // $order->setCreatedAt(new \DateTimeImmutable()); // Normalement déjà fait dans le constructeur de Order

            $this->entityManager->persist($order); // Doctrine va maintenant gérer cet objet Order

            // 3. Créer les objets OrderItem pour chaque article du panier
            foreach ($cartData['items'] as $cartItem) {
                $orderItem = new OrderItem();
                // Assure-toi que setRelatedOrder est le bon nom de méthode dans OrderItem.php
                $orderItem->setRelatedOrder($order); 
                $orderItem->setProductName($cartItem['product']->getTitle());
                $orderItem->setProductPrice($cartItem['product']->getPrice());
                $orderItem->setQuantity($cartItem['quantity']);
                $orderItem->setLineTotal($cartItem['lineTotal']); 
                
                // Optionnel : Lier à l'entité Article si tu as mis la relation productArticle dans OrderItem
                // if ($cartItem['product'] instanceof \App\Entity\Article) {
                //     $orderItem->setProductArticle($cartItem['product']);
                // }

                $this->entityManager->persist($orderItem); // Doctrine gère aussi cet OrderItem
                
                // Si tu as une méthode addOrderItem dans Order.php qui gère la relation bidirectionnelle :
                // $order->addOrderItem($orderItem); 
            }

            // 4. Sauvegarder réellement tout en base de données
            $this->entityManager->flush();

            // 5. Vider le panier et les données de checkout de la session
            $session = $request->getSession();
            $session->remove('cart'); 
            $session->remove('checkout_data'); // Efface les données d'adresse après utilisation

            // 6. Ajouter un message flash de succès final
            $this->addFlash('success', 'Merci ! Votre commande #' . $order->getId() . ' a été enregistrée avec succès.');

            // 7. Rediriger vers la page d'accueil ou une page de remerciement
            return $this->redirectToRoute('app_home');
            // Plus tard, on créera une vraie page de remerciement :
            // return $this->redirectToRoute('app_checkout_thank_you', ['orderId' => $order->getId()]);

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
    
    $this->entityManager->persist($orderItem);
    $order->addOrderItem($orderItem);
}
$this->entityManager->flush();

$session->remove('cart');
$session->remove('checkout_data');
$this->addFlash('success', 'Merci ! votre commande a été passée avec succès !');

return $this->redirectToRoute('app_home');
}
}

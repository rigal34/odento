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

class CheckoutController extends AbstractController
{
    private CartService $cartService;
    private EntityManagerInterface $entityManager;

    public function __construct(CartService $cartService, EntityManagerInterface $entityManager)
    {
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
    }

    #[Route('/checkout', name: 'app_checkout', methods: ['GET', 'POST'])]
    public function checkout(Request $request): Response
    {
        $cartData = $this->cartService->getDetailedCart();

        if (empty($cartData['items'])) {
            $this->addFlash('warning', 'Votre panier est vide. Vous ne pouvez pas passer à la caisse pour le moment.');
            return $this->redirectToRoute('app_panier');
        }

        $form = $this->createForm(CheckoutType::class); // Pas de données initiales pour un formulaire non mappé pour l'instant
        
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
            // Assure-toi que les clés ici ('firstName', 'lastName', etc.) 
            // correspondent EXACTEMENT aux noms des champs dans CheckoutType.php
            $order->setCustomerFirstName($checkoutFormData['firstName'] ?? 'Non fourni');
            $order->setCustomerLastName($checkoutFormData['lastName'] ?? 'Non fourni');
            $order->setCustomerEmail($checkoutFormData['email'] ?? 'Non fourni');
            $order->setCustomerPhone($checkoutFormData['phone'] ?? 'Non fourni');
            $order->setShippingAddress($checkoutFormData['address'] ?? 'Non fournie');
            $order->setShippingApartment($checkoutFormData['apartment'] ?? null);
            $order->setShippingCity($checkoutFormData['city'] ?? 'Non fournie');
            $order->setShippingZipCode($checkoutFormData['zipCode'] ?? 'Non fourni'); // Vérifie 'zipCode' vs 'zip_code'
            $order->setShippingState($checkoutFormData['state'] ?? null);
            $order->setShippingCountry($checkoutFormData['country'] ?? 'Non fourni');
            
            $order->setTotalAmount($cartData['total']);
            $order->setStatus('Nouvelle commande'); 
            // createdAt est normalement mis dans le constructeur de Order

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
                
                // Pour l'image, s'assurer que cette relation est bien remplie
                if (isset($cartItem['product']) && $cartItem['product'] instanceof \App\Entity\Article) {
                    $orderItem->setProductArticle($cartItem['product']); 
                }

                $this->entityManager->persist($orderItem); 
                
                // Si tu as une méthode addOrderItem dans Order.php qui gère la relation bidirectionnelle
                if (method_exists($order, 'addOrderItem')) {
                    $order->addOrderItem($orderItem); 
                }
            }
            
            // 4. Sauvegarder réellement tout en base de données
            $this->entityManager->flush();

            // 5. Vider le panier et les données de checkout de la session
            $session = $request->getSession(); // Récupère la session à nouveau si besoin, ou utilise celle déjà récupérée
            $session->remove('cart'); 
            $session->remove('checkout_data'); // La clé que tu avais utilisée pour stocker les données du formulaire

            // 6. Ajouter un message flash de succès final
            $orderId = $order->getId(); // Récupère l'ID de la commande après le flush
            $this->addFlash('success', 'Merci ! Votre commande #' . $orderId . ' a été enregistrée avec succès.');

            // 7. Rediriger vers la page de remerciement
            return $this->redirectToRoute('app_checkout_thank_you', ['orderId' => $orderId]);

        } elseif ($form->isSubmitted() && !$form->isValid()) {
            // Si le formulaire est soumis mais pas valide
            $this->addFlash('error', 'Le formulaire contient des erreurs. Veuillez vérifier les champs en rouge.');
            // dump($form->getErrors(true, false)); // Tu peux décommenter ça pour déboguer les erreurs de formulaire
        }

        // Si ce n'est pas une soumission (GET) ou si le formulaire soumis était invalide, on affiche la page
        return $this->render('checkout/checkout.html.twig', [
            'controller_name' => 'Processus de Commande',
            'checkout_items' => $cartData['items'],
            'checkout_total_amount' => $cartData['total'],
            'checkout_form' => $form->createView(),
        ]);
    } // <<< ACCOLADE FERMANTE de la méthode checkout()

    #[Route('/commande/merci/{orderId}', name: 'app_checkout_thank_you', methods: ['GET'])]
    public function thankYouPage(int $orderId): Response // EntityManagerInterface est injecté via le constructeur
    {
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        if (!$order) {
            $this->addFlash('warning', 'La commande que vous cherchez n\'a pas été trouvée.');
            return $this->redirectToRoute('app_home');
        }

        // Les dump() de débogage que tu avais mis ici peuvent être enlevés maintenant
        // si tu es sûr que les données arrivent bien.
        // dump('--- Débogage dans thankYouPage ---');
        // dump('Objet Order complet:', $order);
        // ... autres dumps ...
        // dd('--- Fin du débogage dans thankYouPage...');

        return $this->render('checkout/confirmation.html.twig', [ // Ou confirmation.html.twig si c'est le nom que tu as gardé
            'controller_name' => 'Merci pour votre Commande !',
            'order' => $order, 
        ]);
    } // <<< ACCOLADE FERMANTE de la méthode thankYouPage()

    // Si tu avais une méthode confirmation() séparée et que tu ne l'utilises plus, tu peux la supprimer.
    // Si tu avais une méthode placeOrder() séparée, sa logique est maintenant dans checkout(), tu peux la supprimer.

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

        $order = new Order();

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
            if ($cartItem['product'] instanceof \App\Entity\Article) { 
                $orderItem->setProductArticle($cartItem['product']); 
            }
            // 👆👆👆 FIN DE LA LIGNE ESSENTIELLE 👆👆👆
            $this->entityManager->persist($orderItem);

            if (method_exists($order, 'addOrderItem')) { 
                $order->addOrderItem($orderItem); 
            }
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
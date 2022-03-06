<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Repository\OrderRepository;

class OrderController extends AbstractController
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/orders", name="get_orders", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $orders = $this->orderRepository->findAll();

        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->getId(),
                'order_code' => $order->getOrderCode(),
                'product_id' => $order->getProductId(),
                'quantity' => $order->getQuantity(),
                'address' => $order->getAddress(),
                'shipping_date' => $order->getShippingDate(),
            ];
        }

        return new JsonResponse(['orders' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/order/{id}", name="get_order", methods={"GET"})
     */
    public function getOrder($id): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $order->getId(),
            'order_code' => $order->getOrderCode(),
            'product_id' => $order->getProductId(),
            'quantity' => $order->getQuantity(),
            'address' => $order->getAddress(),
            'shipping_date' => $order->getShippingDate(),
        ];

        return new JsonResponse(['order' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/order", name="post_order", methods={"POST"})
     */
    public function postOrder(Request $request): JsonResponse
    {
        $data = $request->query->all();
        empty($data['shipping_date']) ? true : $data["shipping_date"] = \DateTime::createFromFormat('Y-m-d', $data['shipping_date']);

        $orderCode = $data['order_code'];
        $productId = $data['product_id'];
        $quantity = $data['quantity'];
        $address = $data['address'];

        if (empty($orderCode) || empty($productId) || empty($quantity) || empty($address)) {
            throw new NotFoundHttpException('Zorunlu olan değerler boş bırakılamaz.');
        }

        $this->orderRepository->insert($data);

        return new JsonResponse(['status' => 'Sipariş eklendi.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/order/{id}", name="put_order", methods={"PUT"})
     */
    public function putOrder($id, Request $request): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);
        
        $data = $request->query->all();
        
        $orderCode = $data['order_code'];
        $productId = $data['product_id'];
        $quantity = $data['quantity'];
        $address = $data['address'];

        if (empty($orderCode) || empty($productId) || empty($quantity) || empty($address)) {
            throw new NotFoundHttpException('Zorunlu olan değerler boş bırakılamaz.');
        }

        $this->orderRepository->update($order, $data);

        return new JsonResponse(['status' => 'Sipariş güncellendi.']);
    }

    /**
     * @Route("/order/{id}", name="delete_order", methods={"DELETE"})
     */
    public function deleteOrder($id): JsonResponse
    {
        $order = $this->orderRepository->findOneBy(['id' => $id]);

        $this->orderRepository->remove($order);

        return new JsonResponse(['status' => 'Sipariş silindi.']);
    }
}

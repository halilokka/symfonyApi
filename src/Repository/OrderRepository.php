<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private $_order;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $_order)
    {
        parent::__construct($registry, Order::class);
        $this->_order = $_order;
    }

    public function insert($data)
    {
        $order = new Order();

        $order->setOrderCode($data['order_code']);
        $order->setProductId($data['product_id']);
        $order->setQuantity($data['quantity']);
        $order->setAddress($data['address']);
        empty($data['shipping_date']) ? true : $order->setShippingDate(\DateTime::createFromFormat('Y-m-d', $data['shipping_date']));

        $this->_order->persist($order);
        $this->_order->flush();
    }

    public function update(Order $order, $data)
    {
        $order->setOrderCode($data['order_code']);
        $order->setProductId($data['product_id']);
        $order->setQuantity($data['quantity']);
        $order->setAddress($data['address']);
        empty($data['shipping_date']) ? true : $order->setShippingDate(\DateTime::createFromFormat('Y-m-d', $data['shipping_date']));

        $this->_order->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Order $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}

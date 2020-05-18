<?php

namespace App\Libraries;
/**
 * Class Cart
 * @package App\Libraries
 *
 * $p2 = $cart->add(['id' => '45', 'qty' => '2', 'price' => '100.24', 'name' => 'Jeans',
 *             'variations'=>[
 *                  'color'=>'white',
 *                  'dimension'=>[
 *                      'size'=>'M'
 *          ]
 *       ]
 *  ]));
 */
class Cart
{
    /**
     * Cart constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param array $product ['id' => '45', 'qty' => '2', 'price' => '100', 'name' => 'Jeans',
     *                         'variations'=>[ color=>red, dimension=>[size=>'L']]
     * @return string
     * @throws Exception
     */
    public function add(array $product)
    {
        /* Is item array is valid */
        if (!isset($product['id']) || !isset($product['qty']) || !isset($product['price']) || !isset($product['name'])) {
            throw new Exception('The cart array must contain a product ID, quantity, price, and name.');
        }

        /* Xss filter all items */
        $product = array_map_recursive('xss_clean', $product);

        $product['qty'] = filter($product['qty'], 'int');
        $product['price'] = filter($product['price'], 'float');

        /* Generate unique cart_item_id*/
        if (isset($product['variations'])) {
            $id = md5($product['id'] . serialize($product['variations']));
        } else {
            $id = md5($product['id']);
        }

        if ($this->isProductInCart($id)) {

            $qty = $this->getQty($id) + $product['qty'];

            $this->updateQty($id, $qty);

        } else {
            $this->setSession($id, $product);
        }
        $this->updateTotals($id);

        return $id;
    }

    /**
     * @param $id
     * @return bool
     */
    private function isProductInCart($id)
    {
        return isset($_SESSION['cart'][$id]);
    }

    /**
     * @param $id
     * @return |null
     */
    private function getQty($id)
    {
        return $_SESSION['cart'][$id]['qty'] ?? null;
    }

    /**
     * @param $id
     * @param $qty
     */
    private function updateQty($id, $qty)
    {
        $_SESSION['cart'][$id]['qty'] = $qty;
    }

    /**
     * @param $key
     * @param $data
     */
    private function setSession($key, $data)
    {
        $_SESSION['cart'][$key] = $data;
    }

    /**
     * @param $id
     */
    private function updateTotals($id)
    {
        $_SESSION['cart'][$id]['subtotal'] = $this->getProductPrice($id);
    }

    /**
     * @param $id
     * @return float|int|null
     */
    public function getProductPrice($id)
    {
        return isset($_SESSION['cart'][$id]) ? (float)$_SESSION['cart'][$id]['price'] * $this->getQty($id) : null;
    }

    /**
     * @param $id
     */
    public function increase($id)
    {
        $qty = $this->getQty($id) + 1;

        $this->updateQty($id, $qty);
        $this->updateTotals($id);

    }

    /**
     * @param $id
     */
    public function decrease($id)
    {
        if ($this->getQty($id) > 1) {
            $qty = $this->getQty($id) - 1;
            $this->updateQty($id, $qty);
            $this->updateTotals($id);

        } else {

            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
            return;
        }
    }

    /**
     * @return mixed
     */
    public function getTotalPrice(): float
    {
        return (float)$this->getTotals()['total_price'];
    }

    /**
     * @return array|null
     */
    private function getTotals()
    {
        $item = $_SESSION['cart'];//$this->getCart();
        $total_price = 0;
        $total_items = 0;

        if ($item) {
            foreach ($item as $k => $v) {
                $total_price += $v['qty'] * $v['price'];
                $total_items += $v['qty'];
            }
            return ['total_price' => $total_price, 'total_items' => $total_items];

        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getTotalItems(): int
    {
        return (int)$this->getTotals()['total_items'];

    }

    /**
     * @return bool|mixed
     */
    public function getCart()
    {
        //$_SESSION['cart']['total'] = $this->getTotalPrice();
        return $_SESSION['cart'] ?? null;
    }

    /**
     * @param null $id
     */
    function deleteCart($id = null)
    {
        if (isset($_SESSION['cart'])) {
            if ($id) {
                unset($_SESSION['cart'][$id]);
                return;
            }
            unset($_SESSION['cart']);
        }
    }
}

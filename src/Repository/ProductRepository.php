<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use App\Service\ConnectOdooService;

class ProductRepository
{
    /**
     * @var ConnectOdooService
     */
    private $connectOdooService;

    public function __construct(ConnectOdooService $connectOdooService)
    {
        $this->connectOdooService = $connectOdooService;
    }

    public function findAll($number = 20)
    {
        $client = $this->connectOdooService->connectApi();

        $ids = $client->search('product.template', [['sale_ok', '=', true]], 0, $number);

        $fields = ['name', 'base_price', 'categ_id', 'image_medium'];

        $products = $client->read('product.template', $ids, $fields);

        $articles = $this->hydrateArticles($products);

        return $articles;
    }

    public function findByCategory(array $id)
    {
        $client = $this->connectOdooService->connectApi();

        $ids = $client->search('product.template', [['sale_ok', '=', true], ['categ_id', '=', $id]]);
        $fields = ['name', 'base_price', 'categ_id', 'image_medium'];

        $products = $client->read('product.template', $ids, $fields);

        $articles = $articles = $this->hydrateArticles($products);

        return $articles;
    }

    public function findByName($name): array
    {
        $client = $this->connectOdooService->connectApi();
        $ids = $client->search('product.template', [['name', '=ilike', '%' . $name . '%']]);

        $fields = ['name', 'base_price', 'categ_id', 'image_medium'];

        $products = $client->read('product.template', $ids, $fields);

        $articles = $articles = $this->hydrateArticles($products);

        return $articles;
    }

    public function countAll()
    {
        $client = $this->connectOdooService->connectApi();

        $criteria = [
            ['sale_ok', '=', true],
        ];

        $products = $client->search_count('product.template', $criteria);

        return $products;
    }

    private function hydrateArticles($products)
    {
        $articles = [];
        foreach ($products as $product) {
            $category = new Category();
            $category->setName($product['categ_id'][1]);
            $category->setId($product['categ_id'][0]);
            $article = new Product();
            $article->setName($product['name']);
            $article->setPrice($product['base_price']);
            $article->setCategory($category);
            $article->setPicture($product['image_medium']);
            $articles[] = $article;
        }
        return $articles;
    }
}

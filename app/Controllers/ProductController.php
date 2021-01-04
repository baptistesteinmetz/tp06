<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Product;

// entitymanager doesn't seem to be working with require here ....??


class ProductController {

    public function getAll(Request $request, Response $response, $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $products = $entityManager->getRepository('Product')->findAll();
        if($products) {
            $data = [];
            foreach($products as $product) {
                $data[] = [
                    'idProduct' => $product->getIdProduct(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'src' => $product->getSrc(),
                    'description' => $product->getDescription(),
                    'primary_color' => $product->getPrimaryColor(),
                    'secondary_color' => $product->getSecondaryColor(),
                    'platform' => $product->getPlatform(),
                    'release_date' => $product->getReleaseDate(),
                ];
            }
            $response->getBody()->write(json_encode([
                "success" => true,
                "data" => $data
            ]));
        }
        else {
            $response->getBody()->write(json_encode([
                "success" => false,
            ]));
            $response = $response->withStatus(401);
        }
        return $response
        ->withHeader("Content-Type", "application/json")
        ->withHeader('Access-Control-Expose-Headers', '*');
    }



    public function getOne(Request $request, Response $response, $args) {
        require_once  __DIR__ . './../../bootstrap.php';
        $id = intval($args['id']);
        $product = $entityManager->getRepository('Product')->findOneByIdProduct($id);
        if($product) {
            $data = [
                'idProduct' => $product->getIdProduct(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'src' => $product->getSrc(),
                'description' => $product->getDescription(),
                'primary_color' => $product->getPrimaryColor(),
                'secondary_color' => $product->getSecondaryColor(),
                'platform' => $product->getPlatform(),
                'release_date' => $product->getReleaseDate(),
            ];
            $response->getBody()->write(json_encode([
                "success" => true,
                'data' => $data
            ]));
        }
        else {
            $response->getBody()->write(json_encode([
                "success" => false,
            ]));
            $response = $response->withStatus(401);
        }
        return $response
        ->withHeader("Content-Type", "application/json")
        ->withHeader('Access-Control-Expose-Headers', '*');
    }

    public function buyArticle(Request $request, Response $response, $args) {
        require_once __DIR__ . './../../bootstrap.php';
        var_dump($request->getParsedBody());
        $products = $args['data'];
        var_dump($products);
        $price = 0;
        foreach($products as $product) {
            /** @var Product $product  */
            $price += $product->getPrice();
        }

        if($price == 0){
            $response->getBody()->write(json_encode([
                "success" => false,
            ]));
            $response = $response->withStatus(401);
        }
        else {
            $data = 'ok';
            $response->getBody()->write(json_encode([
                "success" => true,
                'data' => $data
            ]));
        }
        return $response
        ->withHeader("Content-Type", "application/json")
        ->withHeader('Access-Control-Expose-Headers', '*');
    }

}
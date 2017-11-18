<?php
/**
 * Created by PhpStorm.
 * User: cpapad
 * Date: 15/11/2017
 * Time: 15:21
 */


require __DIR__.'/../vendor/autoload.php';

//if a productId was supplied, check it was valid
$productIdExists = false;
if(!empty($_GET["productId"])) {
    $sql = "SELECT id from products WHERE id=:id";
    $params = array(
        ':id' => $_GET["productId"],
    );
    $result = Database::executeMysqlQuery(Constants::DEBUG, Database::getInstance()->getDbh(), $sql, $params);
    if ($result['status']) {
        //query executed ok
        if ($result['result']->rowCount()==1) {
            //id found
            $productIdExists = true;
        }
    }
}
if ((!empty($_GET["productId"])) && (!$productIdExists)) {
    header("HTTP/1.0 400 Bad Request");
    echo json_encode(array(
        'status' => 0,
        'error' => "Product ID {$_GET["productId"]} not found."
    ));
    exit(0);
}

//Only proceeds if productId was not supplied or supplied and valid

switch($_SERVER["REQUEST_METHOD"])
{
    case 'GET':
        // Retrive Products
        $sql="SELECT * FROM products";
        $params = array();

        if(!empty($_GET["productId"]))
        {
            $sql.=" WHERE id=:id LIMIT 1";
            $params = array(
                ':id' => $_GET["productId"],
            );
        }
        $result = Database::executeMysqlQuery(Constants::DEBUG, Database::getInstance()->getDbh(), $sql, $params);
        if (!$result['status']) $response = $result['error']; else {
            while ($resultrow = $result['result']->fetch(PDO::FETCH_ASSOC)) {
                $response[] = $resultrow;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);

        break;
    case 'POST':
        // Insert Product
        insert_product();
        break;
    case 'PUT':
        // Update Product
        $product_id=intval($_GET["product_id"]);
        update_product($product_id);
        break;
    case 'DELETE':
        // Delete Product
        if(!empty($_GET["productId"])) {
            $sql = "DELETE FROM products WHERE id=:id";
            $params = array(
                ':id' => $_GET["productId"],
            );
            $result = Database::executeMysqlQuery(Constants::DEBUG, Database::getInstance()->getDbh(), $sql, $params);
            if ($result['status']) {
                $ret = array(
                    'status' => 1,
                    'error' => "Product {$_GET["productId"]} was deleted.",
                );
            } else {
                $ret = array(
                    'status' => 0,
                    'error' => "Product {$_GET["productId"]} was not deleted.",
                );
                if (Constants::DEBUG) {
                    $ret['error_details'] = $result['error']['message'];
                }
            }
        }
        header('Content-Type: application/json');
        echo json_encode($ret);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

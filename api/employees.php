<?php
/**
 * Created by PhpStorm.
 * User: cpapad
 * Date: 15/11/2017
 * Time: 15:21
 */


require __DIR__.'/../vendor/autoload.php';

//if employeeId was supplied, check it was valid
$idExists = false;
if(!empty($_GET["employeeId"])) {
    $sql = "SELECT employeeId from employees WHERE employeeId=:employeeId";
    $params = array(
        ':employeeId' => $_GET["employeeId"],
    );
    $result = Database::executeMysqlQuery(Constants::DEBUG, Database::getInstance()->getDbh(), $sql, $params);
    if ($result['status']) {
        //query executed ok
        if ($result['result']->rowCount()==1) {
            //id found
            $idExists = true;
        }
    }
}
if ((!empty($_GET["employeeId"])) && (!$idExists)) {
    header("HTTP/1.0 400 Bad Request");
    echo json_encode(array(
        'status' => 1,
        'message' => "Employee ID {$_GET["employeeId"]} not found."
    ));
    exit(0);
}

//Only proceeds if employeeId was not supplied or supplied and valid

switch($_SERVER["REQUEST_METHOD"])
{
    case 'GET':
        // Retrive Employees
        $sql="SELECT * FROM Employees";
        $params = array();

        if(!empty($_GET["employeeId"]))
        {
            $sql.=" WHERE employeeId=:employeeId LIMIT 1";
            $params = array(
                ':employeeId' => $_GET["employeeId"],
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
        // Insert Employee
        $sql = "INSERT INTO employees(employeeName, employeeSalary, employeeRole, branchId) VALUES (:employeeName, :employeeSalary, :employeeRole, :branchId)";
        $params = array(
            ':employeeName'   => $_POST["employeeName"],
            ':employeeSalary' => $_POST["employeeSalary"],
            ':employeeRole'   => $_POST["employeeRole"],
            ':branchId'       => $_POST["branchId"],
        );
        $result = Database::executeMysqlQuery(Constants::DEBUG, Database::getInstance()->getDbh(), $sql, $params);
        if ($result['status']) {
            $ret = array(
                'status' => 0,
                'message' => "Employee was saved.",
            );
        } else {
            $ret = array(
                'status' => 1,
                'message' => "Employee was not saved.",
            );
            if (Constants::DEBUG) {
                $ret['error_details'] = $result['error']['message'];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($ret);
        break;
    case 'PUT':
        // Update Employee

        $sql = "UPDATE employees SET employeeName=:employeeName, employeeSalary=:employeeSalary, employeeRole=:employeeRole, branchId=:branchId WHERE employeeId=:employeeId";
        parse_str(file_get_contents("php://input"), $postData);
        $params = array(
            ':employeeId'     => $_GET["employeeId"],
            ':employeeName'   => $postData["employeeName"],
            ':employeeSalary' => $postData["employeeSalary"],
            ':employeeRole'   => $postData["employeeRole"],
            ':branchId'       => $postData["branchId"],
        );
        $result = Database::executeMysqlQuery(Constants::DEBUG, Database::getInstance()->getDbh(), $sql, $params);
        if ($result['status']) {
            $ret = array(
                'status' => 0,
                'message' => "Employee ID {$_GET["employeeID"]} was updated.",
            );
        } else {
            $ret = array(
                'status' => 1,
                'message' => "Employee ID {$_GET["employeeId"]} was not updated.",
            );
            if (Constants::DEBUG) {
                $ret['error_details'] = $result['error']['message'];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($ret);


        break;
    case 'DELETE':
        // Delete Employee
        if(!empty($_GET["employeeId"])) {
            $sql = "DELETE FROM Employees WHERE employeeId=:employeeId";
            $params = array(
                ':employeeId' => $_GET["employeeId"],
            );
            $result = Database::executeMysqlQuery(Constants::DEBUG, Database::getInstance()->getDbh(), $sql, $params);
            if ($result['status']) {
                $ret = array(
                    'status' => 0,
                    'message' => "Employee {$_GET["employeeId"]} was deleted.",
                );
            } else {
                $ret = array(
                    'status' => 1,
                    'message' => "Employee {$_GET["employeeId"]} was not deleted.",
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

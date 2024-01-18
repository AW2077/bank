<?php

    error_reporting(-1); // reports all errors
    ini_set("display_errors", "1"); // shows all errors
    ini_set("log_errors", 1);
    ini_set("error_log", "/tmp/php-error.log");

    header('Content-Type: application/json');
    $result = array();
    
    try {
        $conn = new PDO("sqlsrv:server = tcp:bankswps.database.windows.net,1433; Database = bank_db", "bmaslak", "#kalsamb123");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        $result['error'] = 'Error connecting to SQL Server.';
        die(print_r($e));
    } 

    // SQL Server Extension Sample Code:
    $connectionInfo = array("UID" => "bmaslak", "pwd" => "#kalsamb123", "Database" => "bank_db", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    $serverName = "tcp:bankswps.database.windows.net,1433";
    $conn = sqlsrv_connect	($serverName, $connectionInfo);


    if( !isset($_POST['functionname'])) {$result['error'] = 'No function name!';}

    if( !isset($_POST['arguments'])) {$result['error'] = 'No function arguments!';}

    if( !isset($result['error'])){
        switch($_POST['functionname']){
            case 'checkLoginPassword':
                $query = "SELECT * FROM Klienci";
                $queryResult = sqlsrv_query($conn, $query);

                $result['correct'] = false;
                while($row = sqlsrv_fetch_object($queryResult)){
                    if($row->Login == $_POST['arguments'][0] and $row->Haslo == $_POST['arguments'][1]){
                        $result['correct'] = true;
                        $result['customerID'] = $row->IDklient;
                        break;
                    }
                }
                break;

            case 'createAccount':
                $name = $_POST['arguments'][0];
                $surname = $_POST['arguments'][1];
                $login = $_POST['arguments'][2];
                $password = $_POST['arguments'][3];
                $amount = 0;

                $queryCreateAccount = "INSERT INTO Klienci (Imie, Nazwisko, [Login], Haslo, Stan_konta) values ( '".
                                    $name."', '".$surname."', '".$login."', '".$password."', 0)";

                if($queryResult = sqlsrv_query($conn, $queryCreateAccount)){$result['accountCreated'] = true;}
                break;
            
            case 'getCustomerName':
                $query = "SELECT * FROM Klienci WHERE IDklient = ".$_POST['arguments'][0];
                $queryResult = sqlsrv_query($conn, $query);

                if($row = sqlsrv_fetch_object($queryResult)){
                    $result['name'][0] = $row->Nazwisko;
                    $result['name'][1] = $row->Imie;
                    $result['balance'] = $row->Stan_konta;
                }
                break;

            case 'getHistoryData':

                $customerID = $_POST['arguments'][0];
    

                $queryCustomerInfo = "SELECT * FROM Klienci";
                $queryResult = sqlsrv_query($conn, $queryCustomerInfo);
                while($row = sqlsrv_fetch_object($queryResult)){
                    $id = $row->IDklient;
                    $id = "'".$id."'";
                    $fullName = $row->Imie." ".$row->Nazwisko;
                    $result[$id] = $fullName;
                }
                
                $query = "SELECT * FROM Transakcje WHERE IDnadawca = ".$customerID." OR IDodbiorca = ".$customerID. " Order BY [Data] DESC"; 
                $queryResult = sqlsrv_query($conn, $query);

                $x = 0;
                while($row = sqlsrv_fetch_object( $queryResult )){

                    $result['title'][$x] = $row->Tytuł;
                    $result['source'][$x] = $row->IDnadawca;
                    $result['destination'][$x] = $row->IDodbiorca;
                    $result['amount'][$x] = $row->Kwota;
                    $result['date'][$x] = date_format($row->Data, 'Y-m-d H:i:s');
					$x++;
				}
                $result['numRows'] = $x;
                break;

            case 'transferMoney':

                
                $sourceID= $_POST['arguments'][0];
                $destID = $_POST['arguments'][1];
                $amount = $_POST['arguments'][2];
                $title = $_POST['arguments'][3];

                $queryRowCount = "SELECT * FROM Transakcje";
                $queryResult = sqlsrv_query($conn, $queryRowCount);
                
                $querySufficentFunds = "SELECT * FROM Klienci WHERE IDklient = ".$sourceID;
                $queryCheckExistingAcc = "SELECT * FROM Klienci WHERE IDklient = ".$destID;
                $queryAdd = "UPDATE Klienci 
                            SET Stan_konta = Stan_konta + ".$amount." 
                            WHERE IDklient = ".$destID;
                $querySub = "UPDATE Klienci 
                            SET Stan_konta = Stan_konta - ".$amount." 
                            WHERE IDklient = ".$sourceID;
                $queryAddLog = "INSERT INTO Transakcje (IDnadawca, IDodbiorca, [Kwota], [Data], Tytuł) 
                                VALUES (". intval($sourceID) .", ". intval($destID) .", ". floatval($amount) .",'".date('Y-m-d H:i:s')."' , '". $title ."')";

                $queryResult = sqlsrv_query($conn, $querySufficentFunds);
                $row = sqlsrv_fetch_object($queryResult);
                if($row->Stan_konta < $amount){
                    die('Insufficient funds');
                }
                $queryResult = sqlsrv_query($conn, $queryCheckExistingAcc);
                if(!$row = sqlsrv_fetch_object($queryResult)){
                    die('No such account');
                }

                if($queryResult = sqlsrv_query($conn, $queryAdd)){
                    $result['Addingworked'] = true;
                }
                
                if($queryResult = sqlsrv_query($conn, $querySub)){
                $result['Substractingworked'] = true;
                }
                
                if($queryResult = sqlsrv_query($conn, $queryAddLog)){
                $result['AddingALogworked'] = true;
                }
                
                

                break;

            case 'getLoan':
                $amount = $_POST['arguments'][0];
                $customerID = $_POST['arguments'][1];
                $queryGetLoan = "UPDATE Klienci SET Stan_konta = Stan_konta + ".$amount." WHERE IDklient = ".$customerID;
                
                $queryAddLog = "INSERT INTO Transakcje (IDnadawca, IDodbiorca, [Kwota], [Data], Tytuł) 
                                VALUES (6, ". intval($customerID) .", ". floatval($amount) .",'".date('Y-m-d H:i:s')."' , 'Kredyt')";


                if($queryResult = sqlsrv_query($conn, $queryAddLog)){
                    $result['logSuccesful'] = true;
                }
                if($queryResult = sqlsrv_query($conn, $queryGetLoan)){
                    $result['loanSuccesful'] = true;
                }
                break;    

            default:
            $result['error'] = 'Function not found: '.$_POST['functionname'];
        }
    }

    echo json_encode($result);
?>
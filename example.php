<?php
/**
 * Created by JetBrains PhpStorm.
 * User: grzegorzlech
 * Date: 12-08-06
 * Time: 14:00
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors','TRUE');

define('SECRET_KEY','MY_SECRET_KEY');
define('CONSUMER_KEY', 'MY_CONSUMER_KEY');
define('CALLBACK_URL', 'http://shoploapi/example.php');

session_start();


try
{
    $config = array(
        'api_key'      =>  CONSUMER_KEY,
        'secret_key'   =>  SECRET_KEY,
        'callback_url' =>  CALLBACK_URL,
    );
    $shoploApi = new Shoplo\ShoploApi($config);


    try
    {
        $data = $shoploApi->product->retrieve();
    }
    catch ( \Shoplo\AuthException $e )
    {
        unset($_SESSION['oauth_token']);
        header('Location: '.CALLBACK_URL);
        exit();
    }


    echo "<table>";
    echo "<tr>
                <td>id</td>
                <td>name</td>
                <td>url</td>
                <td>description</td>
                <td>delivery need</td>
              </tr>";
    foreach ( $data as $d )
    {
        echo "<tr>
                <td>".$d['id']."</td>
                <td>".$d['name']."</td>
                <td>".$d['url']."</td>
                <td>".$d['description']."</td>
                <td>".$d['delivery_need']."</td>
              </tr>";
    }
    echo "</table>";
}
catch ( Shoplo\ShoploException $e )
{
    echo 'Throw Shoplo Exception: '.$e->getMessage();
    exit();
}
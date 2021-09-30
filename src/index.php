<?php
require_once '../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use UsersManager;
use PDO;

$logger = new Logger('main');

$logger->pushHandler(new StreamHandler(__DIR__ . '/../log/app.log', Logger::DEBUG));

$logger->info('First message');
$logger->debug('Second message');

print("1/ ok<br/>");

$loader = new FilesystemLoader('../templates');
print("2/ ok<br/>");

$twig= new Environment($loader, ['cache' => '../cache']);
print("3/ ok<br/>");

$manager = new UsersManager($db);

require_once("../conf.php");

try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Si toutes les colonnes sont converties en string

    // Créer une instance de la classe UsersManager (un objet $manager)
    $manager = new UsersManager($db);

    // Récupérer la liste des utilisateurs
    $users = $manager->getAll();

} catch (PDOException $e) {
    print('<br/>Erreur de connexion : ' . $e->getMessage());
}
?>
echo $twig->render('base.html.twig',[
    'title' => 'Liste des utilisateurs',
    'users' => $manager->getAll(),
]);
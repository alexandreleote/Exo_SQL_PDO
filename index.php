<?php

    try /* When initialized we try to run the code */
    {
        $mysqlClient = new PDO( 
            'mysql:host=localhost;dbname=gaulois;charset=utf8',
            'root',
            ''
        );
    }
    catch (Exception $e) /* If there is an error then we display its type */
    {
        die('Erreur : ' . $e->getMessage());
    }

?>

<?php

    $sqlQuery = 'SELECT nom_personnage FROM personnage';

    $gauloisStatement = $mysqlClient->prepare($sqlQuery);

    $gauloisStatement->execute();

    $gaulois = $gauloisStatement->fetchAll();

    foreach ($gaulois as $gauloi) {
?>

    <p><?php echo $gauloi['nom_personnage']; ?></p>

<?php

    }   

?>
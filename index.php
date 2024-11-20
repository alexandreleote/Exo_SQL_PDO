<?php

    function afficherListe() {

        try /* When initialized we try to run the code */
        {
            $mysqlClient = new PDO( 
                'mysql:host=localhost;dbname=gaulois;charset=utf8',
                'root',
                '',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
            );
        }
        catch (Exception $e) /* If there is an error then we display its type */
        {
            die('Erreur : ' . $e->getMessage());
        }

        $sqlQuery = 'SELECT personnage.id_personnage, personnage.nom_personnage, specialite.nom_specialite, lieu.nom_lieu
                        FROM personnage 
                        LEFT JOIN specialite ON specialite.id_specialite = personnage.id_specialite
                        LEFT JOIN lieu ON lieu.id_lieu = personnage.id_lieu
                        ORDER BY personnage.nom_personnage ASC';

        $personnagesStatement = $mysqlClient->prepare($sqlQuery);
    
        $personnagesStatement->execute();
    
        $personnages = $personnagesStatement->fetchAll();

        $result = "<table border=1>
                    <caption>
                        Liste des villageois de Gaule 50 avant J.C.
                    </caption>
                    <thead>
                        <tr>
                            <th>".mb_strtoupper("Prénom")."</th>
                            <th>".mb_strtoupper("Spécialité")."</th>
                            <th>".mb_strtoupper("Lieu d'habitation")."</th>
                        </tr>
                    </thead>
                    <tbody>";
                    foreach ($personnages as $personnage) {
                        $result .=
                        "<tr>
                            <td><a href='details.php?id=".$personnage['id_personnage']."'>".$personnage['nom_personnage']."</a></td>
                            <td>".$personnage['nom_specialite']."</td>
                            <td>".$personnage['nom_lieu']."</td>
                        </tr>";
                    }
        $result .= "</tobdy></table>";
        return $result;
    }

    echo afficherListe();
?>
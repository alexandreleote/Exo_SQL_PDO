<?php

    function afficherDetails() {

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

        $sqlQueryInfos = 'SELECT personnage.id_personnage, personnage.nom_personnage, specialite.nom_specialite, lieu.nom_lieu
                        FROM personnage 
                        LEFT JOIN specialite ON specialite.id_specialite = personnage.id_specialite
                        LEFT JOIN lieu ON lieu.id_lieu = personnage.id_lieu
                        WHERE personnage.id_personnage = :idPersonnage';
        
        $infosStatement = $mysqlClient->prepare($sqlQueryInfos);

        $infosStatement->execute([
            'idPersonnage' => $_GET['id'] /* Avoid security breach by using named variables */
        ]);

        $personnageInfos = $infosStatement->fetch();

        $result = "<h2>Détails concernant :  ".$personnageInfos['nom_personnage']."</h2>
                    <p>Spécialité : <strong>".$personnageInfos['nom_specialite']."</strong></p>
                    <p>Lieu d'habitation : <strong>".$personnageInfos['nom_lieu']."</strong></p>";

        $sqlQueryBataille = 'SELECT personnage.nom_personnage, bataille.nom_bataille, DATE_FORMAT (date_bataille, "%d %m %Y") AS date_formattee, SUM(prendre_casque.qte) AS casques_pris
                        FROM bataille
                        INNER JOIN prendre_casque ON bataille.id_bataille = prendre_casque.id_bataille
                        INNER JOIN personnage ON personnage.id_personnage = prendre_casque.id_personnage
                        WHERE personnage.id_personnage = :idPersonnage
                        GROUP BY bataille.id_bataille';

        $bataillesStatement = $mysqlClient->prepare($sqlQueryBataille);
    
        $bataillesStatement->execute([
            'idPersonnage' => $_GET['id']
        ]);
    
        $batailles = $bataillesStatement->fetchAll();

        $result .= "<table border=1>
                    <caption>
                        Liste des batailles où ".$personnageInfos['nom_personnage']." a participé :<br><br>
                    </caption>
                    <thead>
                        <tr>
                            <th>".mb_strtoupper("Bataille")."</th>
                            <th>".mb_strtoupper("Date de la bataille")."</th>
                            <th>".mb_strtoupper("Nb de casques pris")."</th>
                        </tr>
                    </thead>
                    <tbody>";
                    foreach ($batailles as $bataille) {
                        $result .=
                        "<tr>
                            <td>".$bataille['nom_bataille']."</a></td>
                            <td>".$bataille['date_formattee']."</td>
                            <td>".$bataille['casques_pris']."</td>
                        </tr>";
                    }
        $result .= "</tobdy></table><br>
                    <button><a href='index.php'>Page précédente</a></button>
                    <button><a href='index.php'>Page suivante</a></button>";
        
        $result .= "<br><br><a href='index.php'>Retour Liste</a>";
        return $result;
    }

    echo afficherDetails();
?>
<?php

    function chiffrement($texte)
    {
        echo "<p>Chiffrement ! </p>";
        $texte_chiffre = $texte;
        $cle = "CyberSecurite";
        echo "<p>Longueur : ".strlen($texte)."</p>";
        for($i=0 ; $i<strlen($texte) ; $i++)
        {
            //echo "<p>i : ".$i."</p>";
            $texte_chiffre[$i] = $texte[$i] ^ $cle[$i%strlen($cle)];
            //echo "<p>CHIFFREMENT DE ".htmlspecialchars($texte[$i])." : ".htmlspecialchars($texte_chiffre[$i])."</p>";
        }
        return $texte_chiffre;
    }


    $req_methode = $_SERVER['REQUEST_METHOD'];
    switch($req_methode)
    {
        case 'GET' :
            echo "<p><span style=\"color: red;\">Erreur : </span>La méthode d'envoi des données est GET. Modifier l'attribut method de votre formulaire avec la valeur \"post\".</p>";
            break;
        case 'POST' :
            echo "<p><span style=\"color: green;\">OK : </span>La méthode d'envoi des données est POST : OK.</p>";
            echo "<p>Les données reçues : </p>";
            echo "<pre>".print_r($_POST)."</pre>";


            // On regarde si on est dans la séance SW05 -> le nom du bouton submit doit être submit_SW05
            if(isset($_POST["submit_SW05"]) || isset($_POST["submit_SW06"]))
            {
                if(isset($_POST["submit_SW05"]))
                {
                    echo "<p><span style=\"color: green;\">OK : </span>Vous testez le formulaire de la séance SW05. Pour tester la séance SW06, modifier l'attribut name du bouton submit avec la valeur \"submit_SW06\".</p>";
                }
                if(isset($_POST["submit_SW06"]))
                {
                    echo "<p><span style=\"color: green;\">OK : </span>Vous testez le formulaire de la séance SW06.</p>";
                }
                // On récupère l'adresse IP
                if(isset($_POST['ip_journal']))
                {
                    $ip_journal = $_POST['ip_journal'];
                    echo "<p><span style=\"color: green;\">OK : </span>La boîte à selection fournissant l'adresse IP est bien renseignée.</p>";
                }
                else 
                {
                    echo "<p><span style=\"color: red;\">ERREUR : </span>La boîte à selection fournissant l'adresse IP n'est pas correctement renseignée. Donner la valeur \"ip_journal\" à son attribut name.</p>";
                    break;
                } 
                // On récupère le message à afficher
                if(isset($_POST['texte']))
                {
                    $texte = $_POST['texte'];
                    echo "<p><span style=\"color: green;\">OK : </span>Le champ de saisi du message est bien renseigné.</p>";
                }
                else 
                {
                    echo "<p><span style=\"color: red;\">ERREUR : </span>Le champ de saisi du message à afficher n'est pas correctement renseigné. Donner la valeur \"texte\" à son attribut name.</p>";
                    break;
                }
                // Dans le cas de SW05, on met des valeurs par défaut aux effets d'affichage :
                $effetapparition = "FE";
                $effetaffichage = "MA";
                $dureeaffichage = "WC";
                $effetdisparition = "FE";
                // Si on est dans la séance SW06, on teste également les autres éléments
                if(isset($_POST["submit_SW06"]))
                {
                    if(isset($_POST['effetapparition']))
                    {
                        $effetapparition = $_POST['effetapparition'];
                        echo "<p><span style=\"color: green;\">OK : </span>La boîte à selection de l'effet d'apparition est bien renseignée.</p>";
                    }
                    else 
                    {
                        echo "<p><span style=\"color: red;\">ERREUR : </span>La boîte à selection de l'effet d'apparition n'est pas correctement renseignée. Donner la valeur \"effetapparition\" à son attribut name.</p>";
                        break;
                    } 
                    if(isset($_POST['effetaffichage']))
                    {
                        $effetaffichage = $_POST['effetaffichage'];
                        echo "<p><span style=\"color: green;\">OK : </span>La boîte à selection de l'effet d'affichage est bien renseignée.</p>";
                    }
                    else 
                    {
                        echo "<p><span style=\"color: red;\">ERREUR : </span>La boîte à selection de l'effet d'affichage n'est pas correctement renseignée. Donner la valeur \"effetaffichage\" à son attribut name.</p>";
                        break;
                    } 
                    if(isset($_POST['dureeaffichage']))
                    {
                        $dureeaffichage = $_POST['dureeaffichage'];
                        echo "<p><span style=\"color: green;\">OK : </span>La boîte à selection de la durée d'affichage est bien renseignée.</p>";
                    }
                    else 
                    {
                        echo "<p><span style=\"color: red;\">ERREUR : </span>La boîte à selection de l'effet d'affichage n'est pas correctement renseignée. Donner la valeur \"dureeaffichage\" à son attribut name.</p>";
                        break;
                    } 
                    if(isset($_POST['effetdisparition']))
                    {
                        $effetdisparition = $_POST['effetdisparition'];
                        echo "<p><span style=\"color: green;\">OK : </span>La boîte à selection de l'effet de disparition est bien renseignée.</p>";
                    }
                    else 
                    {
                        echo "<p><span style=\"color: red;\">ERREUR : </span>La boîte à selection de l'effet de diparition n'est pas correctement renseignée. Donner la valeur \"effetdisparition\" à son attribut name.</p>";
                        break;
                    } 

                }
                // On prépare la trame à envoyer et
                echo "<p>Formulaire complet : OK</p>";
                $message_journal_lumineux = "<L1><PA><".$effetapparition."><".$effetaffichage."><".$dureeaffichage."><".$effetdisparition.">".$texte;
                //echo "<br />".htmlspecialchars($message_journal_lumineux);            
                $XORresult = $message_journal_lumineux[0];
                for($i = 1 ; $i<strlen($message_journal_lumineux) ; $i++)
                {
                    $XORresult = $XORresult ^ $message_journal_lumineux[$i];
                }
                $hex = sprintf('%02X', ord($XORresult));
                $message_journal_lumineux = "<ID00>".$message_journal_lumineux.$hex."<E>";
                echo  "<p>Message à envoyer : ".htmlspecialchars($message_journal_lumineux)."<p>";
                
                if (!extension_loaded('sockets')) {
                    die("<p>L'extension sockets n'est pas installée ou n'est pas activée.");
                }

                $socketUDP = socket_create (AF_INET, SOCK_DGRAM, 0 );
                //socket_bind($sock,"localhost");
                if($socketUDP!=false)
                {
                    echo '<p>Creation de la socket de communication avec le serveur du journal lumineux : OK</p>';
                    //printr($socketUDP);
                    echo '<p>Longueur du message à envoyer : '.strlen($message_journal_lumineux).'</p>';
                    $message_journal_lumineux_chiffre = $message_journal_lumineux;
                    echo "<p>Le message est envoyé à : ".$ip_journal.":4321.</p>";
                    //$message_journal_lumineux_chiffre = chiffrement($message_journal_lumineux);
                    //echo "<p>Message chiffre : ".htmlspecialchars($message_journal_lumineux_chiffre)."<p>";
                    $nsbOctetsEmis = socket_sendto($socketUDP, $message_journal_lumineux_chiffre, strlen($message_journal_lumineux_chiffre), 0, $ip_journal, 4321);
                    //echo "<p>Nombre d'octets à envoyer : ".strlen($message_journal_lumineux_chiffre)."</p>";
                    echo "<p>Nombre d'octets emis : ".$nbOctetsEmis."</p>";
                    if($nbOctetsEmis == strlen($message_journal_lumineux_chiffre))
                    {
                        echo "<p>Emission de la trame : OK</p>";
                    }
                    else 
                    {
                        echo "<p>Emission de la trame : ECHEC</p>";
                    }
                    //fclose($socketUDP);
                }
            }
            else
            {
                echo "<p><span style=\"color: red;\">Erreur : </span> L'attribut name du bouton submit doit valoir \"submit_SW05\" si vous êtes dans la séance SW05 ou \"submit_SW06\" si vous êtes dans la séance SW06.</p>";
                break;
            }
            break;

    }

    echo '<p><button type="button" onclick="history.back();">Retour au formulaire</button></p>';


?>
<!DOCTYPE html>
<html>
    <head>
        <title>ww.MBR.com</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link rel="icon" href="img/Sans titre.png" type="image/x-icon">



    </head>
    <body>
       <h1>MBR</h1>
       <h2>add player :</h2>
       <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">name :</label>
        <input type="text" name="name" id="name" required>
        <label for="club">club :</label>
        <input type="text" name="club" id="club" required>
        <label for="num">number :</label>
        <input type="text" name="num" id="num" required>
        <input type="submit" value="Ajouter">
        
    </form>



<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
        try {
            $bd = new PDO('mysql:host=localhost;dbname=myproject', 'root', '');
            $bd->query("SET NAMES 'utf8'");
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['name']) && isset($_POST['club']) && isset($_POST['num'])) {
                $name = $_POST['name'];
                $club = $_POST['club'];
                $num = $_POST['num'];

                $requet = $bd->prepare("INSERT INTO player (name, club, num) VALUES (:name, :club, :num)");
                $requet->bindParam(':name', $name);
                $requet->bindParam(':club', $club);  // Corrected this line
                $requet->bindParam(':num', $num);

                if ($requet->execute()) {
                    echo "Produit enregistré avec succès !";
                } else {
                    echo "Erreur lors de l'enregistrement du produit.";
                }
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Handling the delete request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
        try {
            $bd = new PDO('mysql:host=localhost;dbname=myproject', 'root', ''); // Corrected to use the right database
            $bd->query("SET NAMES 'utf8'");
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $delete_id = $_POST['delete_id'];
            $requet = $bd->prepare("DELETE FROM player WHERE id = :id");
            $requet->bindParam(':id', $delete_id);

            if ($requet->execute()) {
                echo "Produit supprimé avec succès !";
            } else {
                echo "Erreur lors de la suppression du produit.";
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
?>

<h2>the players :</h2>
<?php
    try {
        $bd = new PDO('mysql:host=localhost;dbname=myproject', 'root', '');
        $bd->query("SET NAMES 'utf8'");
        $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $requet = $bd->prepare("SELECT * FROM player");
        $requet->execute();
        $tab = $requet->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($tab)) {
            echo "<table><thead><tr><th>ID</th><th>name</th><th>club</th><th>number</th><th>delet</th></tr></thead><tbody>";
            foreach ($tab as $row) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['club'] . '</td>';
                echo '<td>' . $row['num'] . '</td>';
                echo '<td>
                        <form method="POST" action="' . $_SERVER['PHP_SELF'] . '">
                            <input type="hidden" name="delete_id" value="' . $row['id'] . '">
                            <input type="submit" value="delet">
                        </form>
                      </td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "Aucun produit trouvé.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
?>

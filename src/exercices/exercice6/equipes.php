<!doctype html>
<html>
  <header>
    <link rel="stylesheet" type="text/css" href="stylesheets/main.css" />
</header>
  <body>
    <div id="conteneur">
      <h1>Les équipes de National League</h1>    
      <table border="1">
      <tr>
        <td>ID</td>
        <td>Club</td>
      </tr>
      <?php
        require('ctrl.php');
        $ctrl = new Ctrl();
        $equipes = $ctrl->getEquipes();

        for ($i = 0; $i < count($equipes); $i++) {
          $equipe = $equipes[$i]; // Accéder à l'élément à l'index $i
          echo "<tr>";
          echo "<td>" . ($i + 1) . "</td>"; // Affiche l'ID, qui est l'index + 1
          echo "<td>" . $equipe . "</td>"; // Affiche le nom de l'équipe
          echo "</tr>";
      }
      ?>
      </table>
    </div>
  </body>
</html>
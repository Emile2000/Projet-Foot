<link rel="stylesheet" href="css/Campus%20contest.css"/>
<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=projetfoot;charset=utf8','root','');
if(isset($_GET['id']) AND !empty($_GET['id'])) {
   $getid = htmlspecialchars($_GET['id']);
   $article = $bdd->prepare('SELECT * FROM articles WHERE id = ?');
   $article->execute(array($getid));
   $article = $article->fetch();
   if(isset($_POST['submit_article'])) { echo"a";
      if(isset($_POST['article_pseudo'],$_POST['article_titre'],$_POST['article_contenu']) AND !empty($_POST['article_pseudo']) AND !empty($_POST['article_titre']) AND !empty($_POST['article_contenu'])) { echo"b";
         $article_pseudo = htmlspecialchars($_POST['article_pseudo']);
         $article_titre = htmlspecialchars($_POST['article_titre']);
         $article_contenu = htmlspecialchars($_POST['article_contenu']);
         if(strlen($article_pseudo) < 25) { echo"c";
            $ins = $bdd->prepare('INSERT INTO articles (article_pseudo, article_titre, article_contenu) VALUES (?,?,?)');
            $ins->execute(array($article_pseudo ,$article_titre ,$article_contenu));
            $c_msg = "<span style='color:green'>Votre article a bien été posté</span>";
         } else {
            $c_msg = "Erreur: Le pseudo doit faire moins de 25 caractères";
         }
      } else {
         $c_msg = "Erreur: Tous les champs doivent être complétés";
      }
   }
   

      if(isset($_POST['submit_commentaire'])) {
      if(isset($_POST['pseudo'],$_POST['commentaire']) AND !empty($_POST['pseudo']) AND !empty($_POST['commentaire'])) {
         $pseudo = htmlspecialchars($_POST['pseudo']);
         $commentaire = htmlspecialchars($_POST['commentaire']);
         if(strlen($pseudo) < 25) {
            $ins = $bdd->prepare('INSERT INTO commentaires (pseudo, commentaire, id_article) VALUES (?,?,?)');
            $ins->execute(array($pseudo,$commentaire,$getid));
            $c_msg = "<span style='color:green'>Votre commentaire a bien été posté</span>";
         } else {
            $c_msg = "Erreur: Le pseudo doit faire moins de 25 caractères";
         }
      } else {
         $c_msg = "Erreur: Tous les champs doivent être complétés";
      }
   }
   $commentaires = $bdd->prepare('SELECT * FROM commentaires WHERE id_article = ? ORDER BY id DESC');
   $commentaires->execute(array($getid));
?>
<h2>Articles:</h2>
<p><?= $article['article_titre'] ?></p>
<p>Posté par :<?= $article['article_pseudo'] ?></p>
<p><?= $article['article_contenu'] ?></p>
<br />
<form method="POST">
   <input type="text" name="article_pseudo" placeholder="Votre pseudo" /><br />
   <input name="article_titre" placeholder="Votre titre"></input><br />
   <textarea name="article_contenu" placeholder="Votre contenu"></textarea> <br />
   <input type="submit" value="Poster mon article" name="submit_article" />
</form>
<h2>Commentaires:</h2>
<form method="POST">
   <input type="text" name="pseudo" placeholder="Votre pseudo" /><br />
   <textarea name="commentaire" placeholder="Votre commentaire..."></textarea><br />
   <input type="submit" value="Poster mon commentaire" name="submit_commentaire" />
</form>
<?php if(isset($c_msg)) { echo $c_msg; } ?>
<br /><br />
<?php while($c = $commentaires->fetch()) { ?>
   <b><?= $c['pseudo'] ?>:</b> <?= $c['commentaire'] ?><br />
<?php } ?>
<?php
}
?>
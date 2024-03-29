<?php
ob_start();

if ($details) {
    echo "<h2>Détails de la recette : " . $details['nomRecette'] . "</h2>";
    if ($listIngredients) {
        echo "<h3>Ingrédients : </h3><ul>";
        foreach ($listIngredients as $ingredient) {
            echo "<li>{$ingredient['nomIngredient']} - {$ingredient['quantite']} {$ingredient['uniteMesure']} | environ {$ingredient['prixTotal']} €</li>";
        }
        echo "</ul>";
    } else {
        echo "<h3>Pas d'ingrédients</h3>";
    }
    echo "<h4>Instructions : </h4><p>{$details['instructions']}</p>";
    echo "<a class='btn btn-warning mb-3' href='traitement.php?action=updateRecette&id={$details['id_recette']}'>Modifier</a>";
    echo "<img class='img-fluid' src='{$details['image']}' alt='Image de la recette'/>";
} else {
    echo "Pas de résultats";
}
?>
<script>
    if (document.title != '{$details['nomRecette']}') {
        document.title = '{$details['nomRecette']}';
    } 
</script>


<?php
$content = ob_get_clean();
require_once "index.php";
<?php
ob_start();
?>
<div class="container-fluid">
    <div class="col align-self-center">
        <form action="traitement.php?action=addIngredient" method="POST" enctype="multipart/form-data"
            class="mb-3 mx-auto">
            <p>
                <label class="form-label">
                    Nom de l'ingrédient :
                    <input type="text" name="nomIngredient" placeholder="ex : Poireau" class="form-control">
                </label>
            </p>
            <p>
                <label class="form-label">
                    Prix de l'ingrédient par unité de mesure (€) :
                    <input type="number" name="prixIngredient" placeholder="ex : 0.8" min="0" step="any"
                        class="form-control">
                </label>
            </p>
            <p>
                <label class="form-label">
                    Unité de mesure :
                    <input type="text" name="uniteMesure" placeholder="ex : g, cl, unité..." class="form-control">
                </label>
            </p>
            <p>
                <input class="btn btn-warning" type="submit" name="submit" value="Ajouter l'ingrédient">
            </p>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once "index.php";
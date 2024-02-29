<?php
ob_start();
?>
<div class="container-fluid">
    <div class="col align-self-center">
        <form action="traitement.php?action=add" method="POST" autocomplete="off" enctype="multipart/form-data"
            class="mb-3 mx-auto">
            <p>
                <label class="form-label">
                    Nom de la recette :
                    <input type="text" name="nomRecette" class="form-control">
                </label>
            </p>
            <p>
                <label class="form-label">
                    Temps de préparation :
                    <input type="number" step="any" name="tempsPreparation" class="form-control" min="1">
                </label>
            </p>
            <p>
                <label class="form-label">
                    Instructions :
                    <textarea name="instructions" class="form-control" rows="3"></textarea>
                </label>
            </p>
            <p>
                <label class="form-label">
                    Catégorie :
                    <select name="id_categorie" value="1" class="form-control">
                        <?php foreach ($categories as $categorie) {
                            echo "<option value='{$categorie[' id_categorie']}'>{$categorie['nomCategorie']}</option>";
                        } ?>
                    </select>
                </label>
            </p>
            <p>
                <label class="form-label">
                    Lien de l'image :
                    <input type="text" name="image" class="form-control">
                </label>
            </p>
            <p>
            <fieldset>
                <legend>Ingrédients :</legend>
                <?php foreach (getIngredients($ingredients) as $ingredient) { ?>
                    <div class="form-check checkbox-lg">
                        <label class="form-check-label">
                            <?= $ingredient["nomIngredient"] ?>
                            <input type="checkbox" id="<?= $ingredient["id_ingredient"] ?>"
                                value="<?= $ingredient["id_ingredient"] ?>" name="id_ingredient[]"
                                class="form-control form-check-input" />
                        </label>

                        <label class="form-label" for="<?= $ingredient["id_ingredient"] ?>">
                            Quantité :
                            <input type="number" step="any" value="quantite" name="quantite" class="form-control" min="0">
                        </label>
                    </div>
                <?php } ?>
            </fieldset>
            </p>
            <p>
                <input class="btn btn-warning" type="submit" name="submit" value="Ajouter la recette">
            </p>
        </form>

        <?php
        $content = ob_get_clean();
        require_once "index.php";
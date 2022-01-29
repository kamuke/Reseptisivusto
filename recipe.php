<?php
  include("path.php");
  include(ROOT_PATH . "/includes/set-usercookie.php");
?>
<!DOCTYPE html>
<html>

<head>
  <title>Reseptisivusto</title>
  <?php include(ROOT_PATH . "/includes/header.php");?>
  <!-- Main starts -->
  <main class="main" id="main">
    <section class="recipe-section">
      <div class="container">
        <figure class="recipe-img-container">
          <img class="recipe-img" src="" alt="">
        </figure>
        <header class="recipe-header">
          <div class="left-column">
            <div class="recipe-headline-description">
              <h2 class="headline-recipe"></h2>
              <div class="description">
              </div>
            </div>
          </div>
          <div class="right-column">
            <div class="key-info round-box drop-shadow">
              <div class="rating">
                <div class="stars">
                  <div class="star-icon" data-index="0"><i class="fas fa-star"></i></div>
                  <div class="star-icon" data-index="1"><i class="fas fa-star"></i></div>
                  <div class="star-icon" data-index="2"><i class="fas fa-star"></i></div>
                  <div class="star-icon" data-index="3"><i class="fas fa-star"></i></div>
                  <div class="star-icon" data-index="4"><i class="fas fa-star"></i></div>
                </div>
                <div class="rating-value-count">
                  <span class="rating-value"></span>
                  <span class="rating-count"></span>
                </div>
              </div>
              <div class="cooking-time-container">
                <div class="clock-icon">
                  <i class="far fa-clock"></i>
                </div>
                <span class="cooking-time"></span>
              </div>
              <div class="servings-container">
                <div class="utensils-icon">
                  <img src="assets/svgs/utensils-icon.svg" alt="Utensils icon">
                </div>
                <span class="servings"></span>
              </div>
              <div class="difficulty-container">
                <div class="chef-hat-icon">
                  <img src="assets/svgs/chef-hat-icon.svg" alt="Chef hat icon">
                </div>
                <span class="difficulty"></span>
              </div>
            </div>
          </div>
        </header>
        <section class="recipe-content">
          <div class="left-column">
            <section class="instructions">
                <h3>Valmistus</h3>
                <ol class="instructions-list"></ol>
            </section>
            <section class="tips">
                <h3>Vinkit</h3>
                <ul class="tips-list"></ul>
            </section>
            <section class="keywords">
                <h3>Avainsanat</h3>
            </section>
            <section class="categories">
                <h3>Ruokalajit</h3>
            </section>
            <section class="diet">
                <h3>Ruokavaliot</h3>
            </section>
            <section class="contributors">
                <div class="author-container">
                    <span class="bold">Reseptin laatinut:</span><span class="author"></span>
                </div>
                <div class="date-published-container">
                    <span class="bold">Julkaistu:</span>
                    <span class="date-published"></span>
                </div>
            </section>
          </div>
          <div class="right-column">
            <section class="ingredients round-box drop-shadow">
              <h3>Ainesosat</h3>
              <ul class="ingredients-list"></ul>
            </section>
          </div>
        </section>
      </div>
    </section>
    <section class="similar-section">
      <div class="container">
        <h2>Katso myös nämä</h2>
      </div>
      <div class="card-container">
      </div>
    </section>
  </main>
  <!-- Main ends -->
  <?php include(ROOT_PATH . "/includes/footer.php"); ?>

  <script type="text/javascript" src="assets/js/scripts.js"></script>
  <script type="module" src="assets/js/recipe/recipe.js"></script>
  <script type="module" src="assets/js/recipe/recipe-rating.js"></script>

  </html>
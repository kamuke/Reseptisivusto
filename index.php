<?php
  include("path.php");
  include(ROOT_PATH . "/includes/set-usercookie.php");
?>

<!DOCTYPE html>
<html>

<head>
  <title>Reseptisivusto</title>
  <?php include(ROOT_PATH . "/includes/header.php"); ?>
  <!-- Main starts -->
  <main class="main" id="main">
    <!-- Hero starts -->
    <section class="hero-section">
      <div class="container">
        <div class="hero-inner">
          <h1>
            Herkulliset reseptit niin arkeen kuin juhlaan!
          </h1>
          <!-- Search starts -->
          <section class="search-container">
            <form class="search-form">
            <div class="input-container">
              <input class="search-input drop-shadow" type="search" name="search" placeholder="Hae reseptejä ja ateriakokonaisuuksia"/>
              <div class="icon">
                <i class="fas fa-search"></i>
              </div>
              <button class="button-primary drop-shadow search-button" type="button" name="">
                <span class="button-text">Hae</span>
              </button>
            </div>
            <button class="show-filters-menu button-secondary" type="button" name="button">
              <span class="button-text">Rajaa</span>
              <div class="button-icon">
                <i class="fas fa-chevron-down"></i>
              </div>
              <div class="button-icon hidden">
                <i class="fas fa-chevron-up"></i>
              </div>
            </button>
            <button class="button-secondary" id="empty-search-button" type="button" name="button">
              <span class="button-text">Tyhjennä haku</span>
            </button>
            <!-- Search filters starts -->
            <section class="filters-menu round-box drop-shadow">
              <div class="close-filters-menu">
                <i class="fas fa-times"></i>
              </div>
                <div class="search-filters-group">
                  <div class="filter-group-headline">
                    Ruokalajit
                  </div>
                  <ul class="filter-group">
                    <li>
                      <div>
                        <input id="category-alkupala" value="alkupala" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-alkupala" class="tag">
                          <span>Alkupala</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="category-jalkiruoka-valipala" value="jälkiruoka ja välipala" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-jalkiruoka-valipala" class="tag">
                          <span>Jälkiruoka ja välipala</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="category-keitto" value="keitto" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-keitto" class="tag">
                          <span>Keitto</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="category-leivonnainen" value="leivonnainen" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-leivonnainen" class="tag">
                          <span>Leivonnainen</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="category-pasta" value="pasta" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-pasta" class="tag">
                          <span>Pasta</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="category-pataruoka" value="pataruoka" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-pataruoka" class="tag">
                          <span>Pataruoka</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="category-pizza" value="pizza" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-pizza" class="tag">
                          <span>Pizza</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="category-paaruoka" value="pääruoka" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-paaruoka" class="tag">
                          <span>Pääruoka</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="category-salaatti" value="salaatti" type="checkbox" autocomplete="off" name="category" class="filter">
                        <label for="category-salaatti" class="tag">
                          <span>Salaatti</span>
                        </label>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="search-filters-group">
                  <div class="filter-group-headline">
                    Ruokavaliot
                  </div>
                  <ul class="filter-group">
                    <li>
                      <div>
                        <input id="diet-gluteeniton" value="gluteeniton" type="checkbox" autocomplete="off" name="diet" class="filter">
                        <label for="diet-gluteeniton" class="tag">
                          <span>Gluteeniton</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="diet-kananmunaton" value="kananmunaton" type="checkbox" autocomplete="off" name="diet" class="filter">
                        <label for="diet-kananmunaton" class="tag">
                          <span>Kananmunaton</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="diet-kasvis" value="kasvis" type="checkbox" autocomplete="off" name="diet" class="filter">
                        <label for="diet-kasvis" class="tag">
                          <span>Kasvis</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="diet-maidoton" value="maidoton" type="checkbox" autocomplete="off" name="diet" class="filter">
                        <label for="diet-maidoton" class="tag">
                          <span>Maidoton</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="diet-vegaaninen" value="vegaaninen" type="checkbox" autocomplete="off" name="diet" class="filter">
                        <label for="diet-vegaaninen" class="tag">
                          <span>Vegaaninen</span>
                        </label>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="search-filters-group">
                  <div class="filter-group-headline">
                    Vaikeusaste
                  </div>
                  <ul class="filter-group">
                    <li>
                      <div>
                        <input id="difficulty-helppo" value="1" type="checkbox" autocomplete="off" name="difficulty" class="filter">
                        <label for="difficulty-helppo" class="tag">
                          <span>Helppo</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="difficulty-keskivaikea" value="2" type="checkbox" autocomplete="off" name="difficulty" class="filter">
                        <label for="difficulty-keskivaikea" class="tag">
                          <span>Keskivaikea</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="difficulty-vaikea" value="3" type="checkbox" autocomplete="off" name="difficulty" class="filter">
                        <label for="difficulty-vaikea" class="tag">
                          <span>Vaikea</span>
                        </label>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="search-filters-group">
                  <div class="filter-group-headline">
                    Valmistusaika
                  </div>
                  <ul class="filter-group">
                    <li>
                      <div>
                        <input id="cooking-time-alle-30min" value="30" type="checkbox" autocomplete="off" name="cookingtime" class="filter">
                        <label for="cooking-time-alle-30min" class="tag">
                          <span>Alle 30min</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="cooking-time-alle-1h" value="60" type="checkbox" autocomplete="off" name="cookingtime" class="filter">
                        <label for="cooking-time-alle-1h" class="tag">
                          <span>Alle 1h</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="cooking-time-alle-1h-30min" value="90" type="checkbox" autocomplete="off" name="cookingtime" class="filter">
                        <label for="cooking-time-alle-1h-30min" class="tag">
                          <span>Alle 1h 30min</span>
                        </label>
                      </div>
                    </li>
                    <li>
                      <div>
                        <input id="cooking-time-alle-2h" value="120" type="checkbox" autocomplete="off" name="cookingtime" class="filter">
                        <label for="cooking-time-alle-2h" class="tag">
                          <span>Alle 2h</span>
                        </label>
                      </div>
                    </li>
                  </ul>
                </div>
            </section>
            </form>
            <!-- Search filters ends -->
            <ul class="selected-filters"></ul>
          </section>
        </div>
        <!-- Search container ends -->
      </div>
    </section>
    <!-- Hero ends -->
    <!-- Seasonal starts -->
    <section class="seasonal-section">
      <div class="container">
        <h2 class="carousel-headline"></h2>
      </div>
      <div class="carousel-container">
        <div class="carousel">
          <div class="slider">
          </div>
        </div>
        <div class="carousel-buttons">
          <button class="carousel-button-previous drop-shadow" type="button" name="button"><i class="fas fa-chevron-left"></i></button>
          <button class="carousel-button-next drop-shadow" type="button" name="button"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
    </section>
    <!-- Seasonal ends -->
    <!-- New stars -->
    <section class="new-section">
      <div class="container">
        <h2>Uusimmat reseptit</h2>
      </div>
      <div class="new-recipes card-container">
      </div>
      <div class="container">
        <button id="show-more" class="button-primary drop-shadow" type="button" name="button">
          <span class="button-text">Näytä lisää</span>
        </button>
      </div>
    </section>
    <!-- New ends -->
  </main>
  <!-- Main ends -->
  <?php include(ROOT_PATH . "/includes/footer.php"); ?>

  <script type="text/javascript" src="assets/js/scripts.js"></script>
  <script type="module" src="assets/js/search.js"></script>
  <script type="module" src="assets/js/landing/carousel.js"></script>
  <script type="module" src="assets/js/landing/new-recipes.js"></script>

  </html>
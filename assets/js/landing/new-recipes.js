import{convertCookingTime, convertServings, convertDifficulty} from "../export/convert-info-export.js";
import{getCardHtml} from "../export/html-export.js";

$(document).ready(function () {

  newRecipes();
});

function newRecipes() {
  const showMoreButton = $("#show-more");
  const newRecipesContainer = $(".new-recipes");
  let cardCount = 0;

  getNewRecipes(3);

  showMoreButton.on("click", function () {
    getNewRecipes(cardCount);
  });

  function getNewRecipes(limit) {
      $.ajax({
        url: "api/recipe/read.php?order=1&limit=" + limit,
        contentType: "application/json",
        dataType: "json",
        success: function(response) {
            cardCount = response.length + 6 ;
            newRecipesContainer.empty();
            renderRecipesCards(response);
            checkIfLonger();
        },
        error: function(response) {
          const error = eval("(" + response.responseText + ")");
          newRecipesContainer.html(`<p>${error.message}</p>`);
        }
      });
    }

    function checkIfLonger() {
      let i = cardCount - 3;
      
      if (i % 6 !== 0) {
        showMoreButton.hide();
      }
    }

    function renderRecipesCards(data) {
      const card = getCardHtml();

      for (let i = 0; i < data.length; i++) {
        newRecipesContainer.append(card);
        $(".new-recipes .card:eq(" + i + ") a").attr("href", "recipe.php?id=" + data[i].ID);
        $(".new-recipes .card:eq(" + i + ") .card-img").attr("src", "assets/images/" + data[i].img).attr("alt", data[i].name);
        $(".new-recipes .card:eq(" + i + ") .card-category").html(data[i].type);
        $(".new-recipes .card:eq(" + i + ") .headline-card").html(data[i].name);
        $(".new-recipes .card:eq(" + i + ") .card-desc p").html(data[i].description);
        $(".new-recipes .card:eq(" + i + ") .cooking").html(convertCookingTime(data[i].cookingtime));
        $(".new-recipes .card:eq(" + i + ") .servings").html(convertServings(data[i].servings));
        $(".new-recipes .card:eq(" + i + ") .difficulty").html(convertDifficulty(data[i].difficulty));
        renderCardStars(data[i].ratingValue, i);
      }
    }

    function renderCardStars(rating, counter) {
      const max = Math.round(Number(rating)) - 1;
      for (let i = 0; i <= max; i++) {
        $(".new-recipes .card:eq(" + counter + ") .star-icon:eq(" + i + ")").css('color', '#E5502F');
      }
    }
  }

  export{newRecipes};
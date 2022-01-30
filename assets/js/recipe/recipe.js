import{convertCookingTime, convertServings, convertDifficulty, convertDate,
    convertRatingCount, roundRatingValue} from "../export/convert-info-export.js";
import{getCardHtml} from "../export/html-export.js";

$(document).ready(function () {
    // Get ID from URL
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const ID = urlParams.get('id');

    const img = $(".recipe-img");
    const headline = $(".headline-recipe");
    const description = $(".description");
    const ratingValue = $(".rating-value");
    const ratingCount = $(".rating-count");
    const cookingtime = $(".cooking-time");
    const servings = $(".servings");
    const difficulty = $(".difficulty");
    const ingredientList = $(".ingredients-list");
    const similarCardContainer = $(".similar-section .card-container");
    const recipeSection = $(".recipe-section");
    const similarSection = $(".similar-section");

    getRecipe();

    function getRecipe() {
        $.ajax({
            url: "api/recipe/read-single.php?id=" + ID,
            contentType: "application/json",
            dataType: "json",
            success: function(response) {
                renderRecipe(response);
            },
            error: function(response) {
                const error = eval("(" + response.responseText + ")");
                recipeSection.empty();
                similarSection.remove();
                const container = document.createElement("div");
                container.className = "container not-found";
                const h2 = document.createElement("h2");
                h2.innerText = "Virhe";
                const paragraph = document.createElement("p");
                paragraph.innerText = error.message;
                container.append(h2, paragraph);
                recipeSection.append(container);
            }
        });
    }

    function renderRecipe(data) {
        document.title = data.name + " - Reseptisivusto";
        img.attr("src", "assets/images/" + data.img);
        img.attr("alt", data.name);
        headline.html(data.name);
        description.html(`<p>${data.description}</p>`);
        ratingValue.html(roundRatingValue(data.rating.ratingValue));
        ratingCount.html("(" + convertRatingCount(data.rating.ratingCount) + ")");
        renderStars(Math.round(ratingValue.text()) - 1);
        cookingtime.html(convertCookingTime(data.cookingtime));
        servings.html(convertServings(data.servings));
        difficulty.html(convertDifficulty(data.difficulty));
        renderIngredients(data.ingredients);
        renderInstructions(data.instructions);
        renderTips(data.tips);
        renderTags(data.keywords, "keywords");
        renderTags(data.categories, "categories");
        renderTags(data.diets, "diets");
        renderContributors(data);
        renderSimilarCards(data.similarRecipes);
    }

    function renderSimilarCards(data){
        const card = getCardHtml();

        for (let i = 0; i < data.length; i++) {
            similarCardContainer.append(card);
            $(".similar-section .card:eq(" + i + ") a").attr("href", "recipe.php?id=" + data[i].ID);
            $(".similar-section .card:eq(" + i + ") .card-img").attr("src", "assets/images/" + data[i].img);
            $(".similar-section .card:eq(" + i + ") .card-img").attr("alt", data[i].name);
            $(".similar-section .card:eq(" + i + ") .card-category").html(data[i].type);
            $(".similar-section .card:eq(" + i + ") .headline-card").html(data[i].name);
            $(".similar-section .card:eq(" + i + ") .card-desc p").html(data[i].description);
            $(".similar-section .card:eq(" + i + ") .cooking").html(convertCookingTime(data[i].cookingtime));
            $(".similar-section .card:eq(" + i + ") .servings").html(convertServings(data[i].servings));
            $(".similar-section .card:eq(" + i + ") .difficulty").html(convertDifficulty(data[i].difficulty));
            renderCardStars(data[i].ratingValue, i);
        }
    }

    function renderStars(max) {
        for (let i = 0; i <= max; i++) {
            $(".stars .star-icon:eq(" + i + ")").css('color', '#E5502F');
        }
    }

    function renderCardStars(rating, counter) {
        const max = Math.round(Number(rating)) - 1;
        for (let i = 0; i <= max; i++) {
            $(".similar-section .card:eq(" + counter + ") .star-icon:eq(" + i + ")").css('color', '#E5502F');
        }
    }

    function renderIngredients(data) {
        for (let i = 0; i < Object.keys(data).length; i++) {
            const list = document.createElement("li");
            const ingredientAmount = document.createElement("div");
            let ingredientName = document.createElement("div");
            list.className = "ingredient";
            ingredientAmount.className = "ingredient-amount";
            ingredientAmount.innerText = data[i].amount + " " + data[i].unit;
            ingredientName.className = "ingredient-name";
            ingredientName.innerText = data[i].name;
            list.append(ingredientAmount);
            list.append(ingredientName);
            ingredientList.append(list);
        }
    }

    function renderInstructions(data) {
        if (data.length > 0) {
            const instructionsOrderedList = $(".instructions ol");
            for (let i = 0; i < data.length; i++) {
                const list = document.createElement("li");
                list.className = "recipe-step";
                list.innerText = data[i];
                instructionsOrderedList.append(list);
            }
        }
    }

    function renderTips(data) {
        if (data.length > 0) {
            const tipsUnorderedList = $(".tips-list");
            for (let i = 0; i < data.length; i++) {
                const list = document.createElement("li");
                list.className = "tip";
                list.append(data[i]);
                tipsUnorderedList.append(list);
            }
        } else {
            const tips = $(".tips");
            tips.remove();
        }
    }

    function renderTags(data, tag) {
        let section;
        let link;

        switch (tag) {
            case "keywords":
                section = $(".keywords");
                link = "index.php?search=";
                break;
            case "categories":
                section = $(".categories");
                link = "index.php?category=";
                break;
            case "diets":
                section = $(".diet");
                link = "index.php?diet=";
                break;
        }

        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                const a = document.createElement("a");
                a.className = "tag drop-shadow";
                a.href = link + encodeURI(data[i].name);
                a.innerText = data[i].name;
                section.append(a);
            }
        } else {
            section.remove();
        }
    }

    function renderContributors(data) {
        const author = $(".author");
        const datePublished = $(".date-published");
        author.append("\u00A0", data.author);
        datePublished.append(convertDate(data.published));
        
        if (data.modified) {
            const contributors = $(".contributors");
            const dateModifiedDiv = document.createElement("div");
            const modifiedSpan = document.createElement("span");
            const dateModifiedSpan = document.createElement("span");
            dateModifiedDiv.className = "date-modified-container";
            dateModifiedSpan.className = "date-modified";
            modifiedSpan.className = "bold";
            modifiedSpan.innerText = "Muokattu:";
            dateModifiedSpan.append(convertDate(data.modified));
            dateModifiedDiv.append(modifiedSpan, "\u00A0", dateModifiedSpan);
            contributors.append(dateModifiedDiv);
        }
    }
});
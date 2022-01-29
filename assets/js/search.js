import{convertCookingTime, convertServings, convertDifficulty} from "./export/convert-info-export.js";
import{getSeasonalAndNewSectionHtml, getCardHtml} from "./export/html-export.js";
import{carousel} from "./landing/carousel.js";
import{newRecipes} from "./landing/new-recipes.js";

$(document).ready(function () {
    const main = $("#main");
    const showFilterButton = $(".show-filters-menu");
    const showFilterButtonIcon = $(".show-filters-menu .button-icon");
    const filtersMenu = $(".filters-menu");
    const closeFiltersMenuButton = $(".close-filters-menu");
    const filter = $(".filter");
    const emptySearchButton = $("#empty-search-button");
    const selectedFiltersDiv = $(".selected-filters");
    const searchButton = $(".search-button");
    const searchForm = $(".search-form");
    const searchInput = $(".search-input");
    let selectedFilters = [];
    const arrCheckBoxValues = $("input:checkbox").map(function(){return $(this).val();}).get();

    getParamsFromURLAndAddToForm();

    // On searchButton click, add form values to URL params.
    searchButton.on("click", function() {
        printValuesToURLParams();
    });

    // On showFilterButton click, toggle class "shown" on filtersMenu and toggle showFilterButtonIcon.
    showFilterButton.on("click", function () {
        filtersMenu.toggleClass("shown");
        showFilterButtonIcon.toggle();
    });

    // On closeFiltersMenuButton click, toggle class "shown" on filtersMenu and toggle showFilterButtonIcon.
    closeFiltersMenuButton.on("click", function () {
        filtersMenu.toggleClass("shown");
        showFilterButtonIcon.toggle();
    });


    // When clicking anywhere on page, toggle class "shown" on filtersMenu if it has class "shown"
    $(document).on("click", function (e) {
        if (filtersMenu.hasClass("shown") && $(e.target).closest(".filters-menu, .show-filters-menu").length === 0) {
            filtersMenu.removeClass("shown");
            showFilterButtonIcon.toggle();
        }
    });

    // When filter changes, if the filter changes to checked, add it's value to selectedFilters list.
    // Or remove it from selectedFilters list. Print selected filters and add form's values to URL params.
    filter.on("change", function () {
        const checked = $("label[for='" + this.id + "']").text();

        if ($(this).is(":checked")) {
            selectedFilters.push(checked);
        } else {
            for (let i = 0; i < selectedFilters.length; i++) {
                if (checked === selectedFilters[i]) {
                    selectedFilters.splice(i, 1);
                }
            }
        }

        printSelectedFilters();
        printValuesToURLParams();
    });

    // On emptySearchButton click, clear form
    emptySearchButton.on("click", function () {
        const url = window.location.search;
        if (url !== "") {
            filter.prop("checked", false);
            searchInput.val("");
            selectedFilters = [];
            printSelectedFilters();
            printValuesToURLParams();
        }
    });

    function checkCheckboxesWithArraysValues(array) {
        if (array!== null) {
            for (let i = 0; i < array.length; i++) {
                if (array[i]) {
                    $('input:checkbox[value="' + array[i] + '"]').attr("checked", true);
                    const id = $('input:checkbox[value="' + array[i] + '"]').attr("id");
                    const label = $("label[for='" + id +"']").text();
                    selectedFilters.push(label);
                    printSelectedFilters();
                }
            }
        }
    }

    function getParamsFromURLAndAddToForm() {
        const decodedURL = new URL(decodeURI(window.location));
        const url = new URLSearchParams(decodedURL.searchParams);
        searchInput.val(checkIfNull(url.get("search")));
        const category = checkIfNull(url.get("category")).split(",");
        const diet = checkIfNull(url.get("diet")).split(",");
        const difficulty = checkIfNull(url.get("difficulty")).split(",");
        const cookingtime = checkIfNull(url.get("cookingtime")).split(",");
        const type = checkIfNull(url.get("type")).split(",");
        const arrSearchParamValues = [...category, ...diet, ...difficulty, ...cookingtime, ...type];

        // Check if arrSearchParamValues match 
        const arr = arrSearchParamValues.filter(function(val) {
            if (arrCheckBoxValues.indexOf(val) !== -1 && val !== "") {
                return true;
            }
        });

        checkCheckboxesWithArraysValues(arr);
        printValuesToURLParams();
    }

    function checkIfNull(variable) {
        return variable ? variable : "";
    }

    function printSelectedFilters() {
        selectedFiltersDiv.empty();

        for (let i = 0; i < selectedFilters.length; i++) {
            const list = document.createElement("li");
            const icon = document.createElement("i");
            list.className = "filter-selected";
            icon.className = "fas fa-times";
            list.append(selectedFilters[i], icon);
            selectedFiltersDiv.append(list);
        }

        $(".filter-selected").on("click", function () {
            for (let i = 0; i < selectedFilters.length; i++) {
                if ($(this).text() === selectedFilters[i]) {
                    const id = $("label:contains('" + selectedFilters[i] + "')").attr("for");
                    $('input:checkbox[id="' + id + '"]').prop("checked", false);
                    selectedFilters.splice(i, 1);
                    printValuesToURLParams();
                }
            }

            printSelectedFilters();
        });
    }

    function combineSerializedForm(serializedFormStr) {
        const arrFormData = serializedFormStr.split("&");
        let combinedKeys = {};

        for (let i = 0; i < arrFormData.length; i++) {
            const arrParam = arrFormData[i].split("=");
            const strKey = arrParam[0];
            const strValue = arrParam[1];

            if (strKey !== "" && strValue !== "") {
                if (typeof (combinedKeys[strKey]) === "undefined") {
                    combinedKeys[strKey] = strValue;
                }
                else {
                    combinedKeys[strKey] += "," + strValue;
                }
            }
        }

        let arrKeyValuePairs = [];
        for (const key in combinedKeys) {
            if (combinedKeys.hasOwnProperty(key)) {
                arrKeyValuePairs.push(key + "=" + combinedKeys[key]);
            }
        }

        return arrKeyValuePairs.join("&");
    }

    function printValuesToURLParams() {
        const serializedForm = encodeURI(combineSerializedForm(searchForm.serialize()));
        const url = new URL(window.location);
        const pathname = url.pathname;

        if (serializedForm === "") {
            window.history.pushState({}, '', `${pathname}`);
            $(".search-section").remove();

            if (!($(".seasonal-section").length)) {
                const seasonalNewSection = getSeasonalAndNewSectionHtml();
                main.append(seasonalNewSection);
                carousel();
                newRecipes();
            }
        } else {
            window.history.pushState({}, '', `${pathname}?${serializedForm}`);
            $(".seasonal-section").remove();
            $(".new-section").remove();
            $(".search-section").remove();
            getSearchResults(serializedForm);
        }
    }
    
    function getSearchResults(searchParams) {
        searchParams = decodeURI(searchParams);
        $.ajax({
            url: "api/recipe/search.php?" + searchParams,
            contentType: "application/json",
            dataType: "json",
            success: function(response) {
                renderSearchResultsCards(response);
            },
            error: function(response) {
                const error = eval("(" + response.responseText + ")");
                main.append('<section class="search-section"><div class="container"><h2></h2></div><div class="card-container"></div></section>');
                $(".search-section h2").html(`${error.message}`);
            }
        });
    }

    function renderSearchResultsCards(data) {
        const card = getCardHtml();
        const searchSection = document.createElement("section");
        const containerDiv = document.createElement("div");
        const h2 = document.createElement("h2");
        const searchResultsDiv = document.createElement("div");
        searchSection.className = "search-section";
        containerDiv.className = "container";
        h2.innerText = "Hakutuloksia " + data.length;
        searchResultsDiv.className = "search-results card-container";
        containerDiv.append(h2);
        searchSection.append(containerDiv, searchResultsDiv);
        main.append(searchSection);

        for (let i = 0; i < data.length; i++) {
            $(".search-results").append(card);
            $(".card:eq(" + i + ") a").attr("href", "recipe.php?id=" + data[i].ID);
            $(".card:eq(" + i + ") .card-img").attr("src", "assets/images/" + data[i].img);
            $(".card:eq(" + i + ") .card-img").attr("alt", data[i].name);
            $(".card:eq(" + i + ") .card-category").html(data[i].type);
            $(".card:eq(" + i + ") .headline-card").html(data[i].name);
            $(".card:eq(" + i + ") .card-desc p").html(data[i].description);
            $(".card:eq(" + i + ") .cooking").html(convertCookingTime(data[i].cookingtime));
            $(".card:eq(" + i + ") .servings").html(convertServings(data[i].servings));
            $(".card:eq(" + i + ") .difficulty").html(convertDifficulty(data[i].difficulty));
            renderCardStars(data[i].ratingValue, i);
        }
    }

    function renderCardStars(rating, counter) {
        const max = Math.round(Number(rating)) - 1;
        for (let i = 0; i <= max; i++) {
            $(".search-results .card:eq(" + counter + ") .star-icon:eq(" + i + ")").css('color', '#E5502F');
        }
    }
});